<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\EnvelopeManagement\Application\Envelope\CommandHandler\DeleteEnvelopeCommandHandler;
use App\EnvelopeManagement\Application\Envelope\CommandHandler\DeleteEnvelopeCommandHandlerException;
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

class DeleteEnvelopeCommandHandlerTest extends TestCase
{
    private DeleteEnvelopeCommandHandler $deleteEnvelopeCommandHandler;
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

        $this->deleteEnvelopeCommandHandler = new DeleteEnvelopeCommandHandler(
            $this->envelopeCommandRepository,
            $this->logger,
        );
    }

    public function testDeleteEnvelopeSuccess(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 1);
        $envelopeToDelete = $this->generateEnvelope('Electricity', '80.00', '80.00', 2);
        $envelopeToDelete->setParent($parentEnvelope);

        $deleteEnvelopeCommand = new DeleteEnvelopeCommand($envelopeToDelete);

        $this->envelopeCommandRepository->expects($this->once())->method('delete');

        $this->deleteEnvelopeCommandHandler->__invoke($deleteEnvelopeCommand);
    }

    public function testDeleteEnvelopeFailure(): void
    {
        $parentEnvelope = $this->generateEnvelope('Bills', '100.00', '200.00', 1);
        $envelopeToDelete = $this->generateEnvelope('Electricity', '80.00', '80.00', 2);
        $envelopeToDelete->setParent($parentEnvelope);

        $deleteEnvelopeCommand = new DeleteEnvelopeCommand($envelopeToDelete);

        $this->envelopeCommandRepository->expects($this->once())->method('delete')->willThrowException(new \Exception());
        $this->expectException(DeleteEnvelopeCommandHandlerException::class);
        $this->expectExceptionMessage('An error occurred while deleting an envelope in DeleteEnvelopeCommandHandler');

        $this->deleteEnvelopeCommandHandler->__invoke($deleteEnvelopeCommand);
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
