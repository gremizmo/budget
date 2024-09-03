<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Envelope\Command\EditEnvelopeCommand;
use App\EnvelopeManagement\Application\Envelope\CommandHandler\EditEnvelopeCommandHandler;
use App\EnvelopeManagement\Application\Envelope\CommandHandler\EditEnvelopeCommandHandlerException;
use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Application\Envelope\Dto\EditEnvelopeInput;
use App\EnvelopeManagement\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Envelope\Builder\EditEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactoryException;
use App\EnvelopeManagement\Domain\Envelope\Factory\EditEnvelopeFactory;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\EditEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\EditEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\EditEnvelopeTitleValidator;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use App\EnvelopeManagement\Infrastructure\Shared\Adapter\LoggerAdapter;
use App\EnvelopeManagement\Infrastructure\Shared\Adapter\UuidAdapter;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EditEnvelopeCommandHandlerTest extends TestCase
{
    private EditEnvelopeCommandHandler $editEnvelopeCommandHandler;
    private LoggerInterface $logger;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeCommandRepositoryInterface&MockObject $envelopeCommandRepository;
    private EditEnvelopeFactory $editEnvelopeFactory;
    private CreateEnvelopeFactory $createEnvelopeFactory;
    private UuidAdapter $uuidAdapter;

    protected function setUp(): void
    {
        $this->envelopeCommandRepository = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->logger = new LoggerAdapter($this->createMock(PsrLoggerInterface::class));
        $this->uuidAdapter = new UuidAdapter();

        $this->editEnvelopeFactory = new EditEnvelopeFactory($this->logger, new EditEnvelopeBuilder(
            new EditEnvelopeTargetBudgetValidator(),
            new EditEnvelopeCurrentBudgetValidator(),
            new EditEnvelopeTitleValidator($this->queryBus),
            $this->logger,
        ));

        $this->createEnvelopeFactory = new CreateEnvelopeFactory($this->logger, new CreateEnvelopeBuilder(
            new CreateEnvelopeTargetBudgetValidator(),
            new CreateEnvelopeCurrentBudgetValidator(),
            new CreateEnvelopeTitleValidator($this->queryBus),
            $this->uuidAdapter,
            $this->logger,
            Envelope::class,
        ));

        $this->editEnvelopeCommandHandler = new EditEnvelopeCommandHandler(
            $this->envelopeCommandRepository,
            $this->editEnvelopeFactory,
            $this->logger,
        );
    }

    /**
     * @throws EditEnvelopeCommandHandlerException|CreateEnvelopeFactoryException
     */
    public function testEditEnvelopeWithoutParentSuccess(): void
    {
        $envelopeToEdit = $this->generateEnvelope('Groceries', '100.00', '200.00');
        $editEnvelopeInput = new EditEnvelopeInput('Groceries', '150.00', '200.00');
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput);

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws EditEnvelopeCommandHandlerException|CreateEnvelopeFactoryException
     */
    public function testEditEnvelopeWithParentSuccess(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 'parent-uuid');
        $envelopeToEdit = $this->generateEnvelope('Electricity', '100.00', '100.00', 'test-uuid', parentEnvelope: $parentEnvelope);
        $editEnvelopeInput = new EditEnvelopeInput('Electricity', '80.00', '100.00', 'edit-uuid');
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws EditEnvelopeCommandHandlerException|CreateEnvelopeFactoryException
     */
    public function testSetParentOnEditSuccess(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 'parent-uuid');
        $envelopeToEdit = $this->generateEnvelope('Electricity', '100.00', '100.00', 'test-uuid');
        $editEnvelopeInput = new EditEnvelopeInput('Electricity', '80.00', '100.00', $parentEnvelope->getUuid());
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws EditEnvelopeCommandHandlerException|CreateEnvelopeFactoryException
     */
    public function testSetParentOnEditTargetBudgetIsBiggerThanParentMaxAllowableFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 'parent-uuid');
        $envelopeToEdit = $this->generateEnvelope('Electricity', '100.00', '100.00', 'test-uuid');
        $editEnvelopeInput = new EditEnvelopeInput('Electricity', '80.00', '110.00', $parentEnvelope->getUuid());
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(EditEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while editing an envelope in EditEnvelopeCommandHandler');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws EditEnvelopeCommandHandlerException|CreateEnvelopeFactoryException
     */
    public function testCurrentBudgetIsLessThanParentTargetBudgetFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 'parent-uuid');
        $envelopeToEdit = $this->generateEnvelope('Electricity', '80.00', '80.00', 'test-uuid');
        $envelopeToEdit->setParent($parentEnvelope);
        $editEnvelopeInput = new EditEnvelopeInput('Electricity', '210.00', '210.00', $parentEnvelope->getUuid());
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(EditEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while editing an envelope in EditEnvelopeCommandHandler');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function testValidateTitleDoesNotAlreadyExists(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 'parent-uuid');
        $envelopeToEdit = $this->generateEnvelope('Electricity', '80.00', '80.00', 'uuid');
        $envelopeToEdit->setParent($parentEnvelope);
        $editEnvelopeInput = new EditEnvelopeInput('Electricity', '180.00', '180.00', $parentEnvelope->getUuid());
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput, $parentEnvelope);

        $this->queryBus->expects($this->once())->method('query')->willReturn((new Envelope())->setUuid('different-uuid')->setTitle('Electricity'));
        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(EditEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while editing an envelope in EditEnvelopeCommandHandler');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function testSetParentWithSameIdAsCurrentEnvelopeFailure(): void
    {
        $envelopeToEdit = $this->generateEnvelope('Electricity', '80.00', '80.00');

        $editEnvelopeInput = new EditEnvelopeInput('Electricity', '100.00', '100.00', 'test-uuid');
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput, $envelopeToEdit);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(EditEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while editing an envelope in EditEnvelopeCommandHandler');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function testChildrenCurrentBudgetExceedsCurrentBudgetFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 'parent-uuid');
        $envelopeToEdit = $this->generateEnvelope('Electricity', '80.00', '100.00', 'edit-uuid', parentEnvelope: $parentEnvelope);
        $childEnvelope = $this->generateEnvelope('Gaz', '20.00', '20.00', 'child-uuid', parentEnvelope: $envelopeToEdit);
        $parentEnvelope->addChild($childEnvelope);
        $parentEnvelope->addChild($envelopeToEdit);
        $envelopeToEdit->addChild($childEnvelope);
        $editEnvelopeInput = new EditEnvelopeInput('Water', '10.00', '100.00', $parentEnvelope->getUuid());
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(EditEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while editing an envelope in EditEnvelopeCommandHandler');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    public function testCurrentBudgetExceedsTargetBudgetFailure(): void
    {
        $envelopeToEdit = $this->generateEnvelope('Electricity', '80.00', '80.00');

        $editEnvelopeInput = new EditEnvelopeInput('Electricity', '40.00', '20.00');
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(EditEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while editing an envelope in EditEnvelopeCommandHandler');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function testChildrenTargetBudgetsExceedsEnvelopeTargetBudgetFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 'parent-uuid');
        $envelopeToEdit = $this->generateEnvelope('Electricity', '80.00', '100.00', 'edit-uuid', parentEnvelope: $parentEnvelope);
        $childEnvelope = $this->generateEnvelope('Gaz', '5.00', '20.00', 'child-uuid', parentEnvelope: $envelopeToEdit);
        $parentEnvelope->addChild($childEnvelope);
        $parentEnvelope->addChild($envelopeToEdit);
        $envelopeToEdit->addChild($childEnvelope);
        $editEnvelopeInput = new EditEnvelopeInput('Water', '10.00', '10.00', $parentEnvelope->getUuid());
        $editEnvelopeCommand = new EditEnvelopeCommand($envelopeToEdit, $editEnvelopeInput, $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(EditEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while editing an envelope in EditEnvelopeCommandHandler');

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    private function generateEnvelope(
        string $title,
        string $currentBudget,
        string $targetBudget,
        ?string $uuid = null,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        return $this->createEnvelopeFactory->createFromDto(
            new CreateEnvelopeInput($title, $currentBudget, $targetBudget),
            $parentEnvelope,
            'test-uuid',
        )
            ->setUuid($uuid ?? $this->uuidAdapter->generate())
        ;
    }
}
