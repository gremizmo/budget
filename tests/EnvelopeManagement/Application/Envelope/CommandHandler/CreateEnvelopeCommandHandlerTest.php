<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Envelope\Command\CreateEnvelopeCommand;
use App\EnvelopeManagement\Application\Envelope\CommandHandler\CreateEnvelopeCommandHandler;
use App\EnvelopeManagement\Application\Envelope\CommandHandler\CreateEnvelopeCommandHandlerException;
use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactoryException;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use App\EnvelopeManagement\Infrastructure\Shared\Adapter\LoggerAdapter;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeCommandHandlerTest extends TestCase
{
    private CreateEnvelopeCommandHandler $createEnvelopeCommandHandler;
    private LoggerInterface $logger;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeCommandRepositoryInterface&MockObject $envelopeCommandRepository;
    private CreateEnvelopeFactory $createEnvelopeFactory;

    protected function setUp(): void
    {
        $this->envelopeCommandRepository = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->logger = new LoggerAdapter($this->createMock(PsrLoggerInterface::class));

        $this->createEnvelopeFactory = new CreateEnvelopeFactory($this->logger, new CreateEnvelopeBuilder(
            new CreateEnvelopeTargetBudgetValidator(),
            new CreateEnvelopeCurrentBudgetValidator(),
            new CreateEnvelopeTitleValidator($this->queryBus),
            $this->logger,
            Envelope::class,
        ));

        $this->createEnvelopeCommandHandler = new CreateEnvelopeCommandHandler(
            $this->envelopeCommandRepository,
            $this->createEnvelopeFactory,
            $this->logger,
        );
    }

    /**
     * @throws CreateEnvelopeCommandHandlerException
     */
    public function testCreateEnvelopeWithoutParentSuccess(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '100.00', '200.00');
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 1);

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeCommandHandlerException
     */
    public function testCreateEnvelopeWithParentSuccess(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '100.00', '200.00', 1);
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 1);

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeCommandHandlerException
     */
    public function testCreateEnvelopeWithoutParentCurrentBudgetBiggerThanTargetBudgetFailure(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '300.00', '200.00');
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 1);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(CreateEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while creating an envelope in CreateEnvelopeCommandHandler');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeCommandHandlerException
     * @throws CreateEnvelopeFactoryException
     */
    public function testCreateEnvelopeWithParentWithCurrentBudgetBiggerThanParentTargetBudgetFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Parent', '100.00', '200.00', 1);
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '210.00', '210.00', $parentEnvelope->getId());
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 1, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(CreateEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while creating an envelope in CreateEnvelopeCommandHandler');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeCommandHandlerException
     */
    public function testCreateEnvelopeWithTitleAlreadyExistsFailure(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '300.00', '200.00');
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 1);

        $this->queryBus->expects($this->once())->method('query')->willReturn((new Envelope())->setTitle('Groceries'));
        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(CreateEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while creating an envelope in CreateEnvelopeCommandHandler');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeCommandHandlerException
     * @throws CreateEnvelopeFactoryException
     */
    public function testUpdateAncestorsCurrentBudgetSuccess(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 1);
        $parentEnvelope2 = $this->generateEnvelope('Electricity', '50.00', '100.00', 2, $parentEnvelope);
        $createEnvelopeInput = new CreateEnvelopeInput('Gaz', '50.00', '50.00', $parentEnvelope2->getId());
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 1, $parentEnvelope2);

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeCommandHandlerException
     * @throws CreateEnvelopeFactoryException
     */
    public function testValidateTargetBudgetIsLessThanParentTargetBudgetFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 1);
        $createEnvelopeInput = new CreateEnvelopeInput('Gaz', '50.00', '250.00', $parentEnvelope->getId());
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 1, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(CreateEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while creating an envelope in CreateEnvelopeCommandHandler');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function testValidateParentEnvelopeChildrenTargetBudgetIsLessThanTargetBudgetInputFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 1);
        $parentEnvelope2 = $this->generateEnvelope('Electricity', '50.00', '50.00', 2, $parentEnvelope);
        $parentEnvelope3 = $this->generateEnvelope('Water', '40.00', '50.00', 3, $parentEnvelope);
        $parentEnvelope->addChild($parentEnvelope2);
        $parentEnvelope->addChild($parentEnvelope3);
        $createEnvelopeInput = new CreateEnvelopeInput('Gaz', '50.00', '50.00', $parentEnvelope->getId());
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 1, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(CreateEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while creating an envelope in CreateEnvelopeCommandHandler');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    private function generateEnvelope(
        string $title,
        string $currentBudget,
        string $targetBudget,
        int $id,
        ?Envelope $parentEnvelope = null,
    ): Envelope {
        $envelope = $this->createEnvelopeFactory->createFromDto(
            new CreateEnvelopeInput($title, $currentBudget, $targetBudget),
            $parentEnvelope,
            1,
        );

        return $envelope->setId($id);
    }
}
