<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Envelope\Command\CreateEnvelopeCommand;
use App\EnvelopeManagement\Application\Envelope\CommandHandler\CreateEnvelopeCommandHandler;
use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Domain\Envelope\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Envelope\Exception\CurrentBudgetException;
use App\EnvelopeManagement\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\EnvelopeManagement\Domain\Envelope\Exception\TargetBudgetException;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Infrastructure\Envelope\Adapter\UuidAdapter;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeCommandHandlerTest extends TestCase
{
    private CreateEnvelopeCommandHandler $createEnvelopeCommandHandler;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeCommandRepositoryInterface&MockObject $envelopeCommandRepository;
    private CreateEnvelopeFactory $createEnvelopeFactory;
    private UuidAdapter $uuidAdapter;

    protected function setUp(): void
    {
        $this->envelopeCommandRepository = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->uuidAdapter = new UuidAdapter();

        $this->createEnvelopeFactory = new CreateEnvelopeFactory(new CreateEnvelopeBuilder(
            new CreateEnvelopeTargetBudgetValidator(),
            new CreateEnvelopeCurrentBudgetValidator(),
            new CreateEnvelopeTitleValidator($this->queryBus),
            $this->uuidAdapter,
            Envelope::class,
        ));

        $this->createEnvelopeCommandHandler = new CreateEnvelopeCommandHandler(
            $this->envelopeCommandRepository,
            $this->createEnvelopeFactory,
        );
    }

    public function testCreateEnvelopeWithoutParentSuccess(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '100.00', '200.00');
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 'test-uuid');

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testCreateEnvelopeWithParentSuccess(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '100.00', '200.00', 'test-uuid');
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 'test-uuid');

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testCreateEnvelopeWithoutParentCurrentBudgetBiggerThanTargetBudgetFailure(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '300.00', '200.00');
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 'test-uuid');

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(CurrentBudgetException::class);
        $this->expectExceptionMessage('Current budget of envelope exceeds the envelope\'s target budget');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testCreateEnvelopeWithParentWithCurrentBudgetBiggerThanParentTargetBudgetFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Parent', '100.00', '200.00');
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '210.00', '210.00', $parentEnvelope->getUuid());
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 'test-uuid', $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(CurrentBudgetException::class);
        $this->expectExceptionMessage('Current budget of parent envelope exceeds the parent envelope\'s target budget');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testCreateEnvelopeWithTitleAlreadyExistsFailure(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput('Groceries', '300.00', '200.00');
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 'test-uuid');

        $this->queryBus->expects($this->once())->method('query')->willReturn((new Envelope())->setTitle('Groceries'));
        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(EnvelopeTitleAlreadyExistsForUserException::class);
        $this->expectExceptionMessage('Envelope with this title already exists');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testUpdateAncestorsCurrentBudgetSuccess(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00');
        $parentEnvelope2 = $this->generateEnvelope('Electricity', '50.00', '100.00', $parentEnvelope);
        $createEnvelopeInput = new CreateEnvelopeInput('Gaz', '50.00', '50.00', $parentEnvelope2->getUuid());
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 'test-uuid', $parentEnvelope2);

        $this->envelopeCommandRepository->expects($this->once())->method('save');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testValidateTargetBudgetIsLessThanParentTargetBudgetFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00');
        $createEnvelopeInput = new CreateEnvelopeInput('Gaz', '50.00', '250.00', $parentEnvelope->getUuid());
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 'test-uuid', $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(TargetBudgetException::class);
        $this->expectExceptionMessage('Total target budget of children envelopes exceeds the parent envelope\'s target budget');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testValidateParentEnvelopeChildrenTargetBudgetIsLessThanTargetBudgetInputFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00');
        $parentEnvelope2 = $this->generateEnvelope('Electricity', '50.00', '50.00', $parentEnvelope);
        $parentEnvelope3 = $this->generateEnvelope('Water', '40.00', '50.00', $parentEnvelope);
        $parentEnvelope->addChild($parentEnvelope2);
        $parentEnvelope->addChild($parentEnvelope3);
        $createEnvelopeInput = new CreateEnvelopeInput('Gaz', '50.00', '50.00', $parentEnvelope->getUuid());
        $createEnvelopeCommand = new CreateEnvelopeCommand($createEnvelopeInput, 'test-uuid', $parentEnvelope);

        $this->envelopeCommandRepository->expects($this->never())->method('save');
        $this->expectException(TargetBudgetException::class);
        $this->expectExceptionMessage('Target budget exceeds parent max allowable budget');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    private function generateEnvelope(
        string $title,
        string $currentBudget,
        string $targetBudget,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        return $this->createEnvelopeFactory->createFromDto(
            new CreateEnvelopeInput($title, $currentBudget, $targetBudget),
            $parentEnvelope,
            'test-uuid',
        );
    }
}
