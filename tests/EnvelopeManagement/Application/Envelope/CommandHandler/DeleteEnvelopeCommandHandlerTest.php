<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Command\DeleteEnvelopeCommand;
use App\EnvelopeManagement\Application\CommandHandler\DeleteEnvelopeCommandHandler;
use App\EnvelopeManagement\Application\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Factory\CreateEnvelopeFactory;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Infrastructure\Adapter\UuidAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteEnvelopeCommandHandlerTest extends TestCase
{
    private DeleteEnvelopeCommandHandler $deleteEnvelopeCommandHandler;
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

        $this->deleteEnvelopeCommandHandler = new DeleteEnvelopeCommandHandler(
            $this->envelopeCommandRepository,
        );
    }

    public function testDeleteEnvelopeSuccess(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00');
        $envelopeToDelete = $this->generateEnvelope('Electricity', '80.00', '80.00');
        $envelopeToDelete->setParent($parentEnvelope);

        $deleteEnvelopeCommand = new DeleteEnvelopeCommand($envelopeToDelete);

        $this->envelopeCommandRepository->expects($this->once())->method('delete');

        $this->deleteEnvelopeCommandHandler->__invoke($deleteEnvelopeCommand);
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
