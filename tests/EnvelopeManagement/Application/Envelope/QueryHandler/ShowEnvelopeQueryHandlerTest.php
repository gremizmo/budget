<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Application\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Application\QueryHandler\EnvelopeNotFoundException;
use App\EnvelopeManagement\Application\QueryHandler\ShowEnvelopeQueryHandler;
use App\EnvelopeManagement\Domain\Adapter\LoggerInterface;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Factory\CreateEnvelopeFactory;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Infrastructure\Adapter\LoggerAdapter;
use App\EnvelopeManagement\Infrastructure\Adapter\UuidAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

class ShowEnvelopeQueryHandlerTest extends TestCase
{
    private ShowEnvelopeQueryHandler $showEnvelopeQueryHandler;
    private LoggerInterface $logger;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeQueryRepositoryInterface&MockObject $envelopeQueryRepository;
    private CreateEnvelopeFactory $createEnvelopeFactory;
    private UuidAdapter $uuidAdapter;

    protected function setUp(): void
    {
        $this->envelopeQueryRepository = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->logger = new LoggerAdapter($this->createMock(PsrLoggerInterface::class));
        $this->uuidAdapter = new UuidAdapter();

        $this->createEnvelopeFactory = new CreateEnvelopeFactory(new CreateEnvelopeBuilder(
            new CreateEnvelopeTargetBudgetValidator(),
            new CreateEnvelopeCurrentBudgetValidator(),
            new CreateEnvelopeTitleValidator($this->queryBus),
            $this->uuidAdapter,
            Envelope::class,
        ));

        $this->showEnvelopeQueryHandler = new ShowEnvelopeQueryHandler(
            $this->envelopeQueryRepository,
            $this->logger,
        );
    }

    public function testShowEnvelopeSuccess(): void
    {
        $envelopeToShow = $this->generateEnvelope('Electricity', '80.00', '80.00', 'test-envelope-uuid');
        $showEnvelopeQuery = new ShowEnvelopeQuery($envelopeToShow->getUuid(), 'test-user-uuid');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willReturn($envelopeToShow);

        $envelope = $this->showEnvelopeQueryHandler->__invoke($showEnvelopeQuery);

        $this->assertEquals($envelopeToShow, $envelope);
    }

    public function testShowEnvelopeReturnsNull(): void
    {
        $envelopeToShow = $this->generateEnvelope('Electricity', '80.00', '80.00', 'test-envelope-uuid');
        $showEnvelopeQuery = new ShowEnvelopeQuery($envelopeToShow->getUuid(), 'test-user-uuid');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willReturn(null);
        $this->expectException(EnvelopeNotFoundException::class);
        $this->expectExceptionMessage('Envelope not found');

        $this->showEnvelopeQueryHandler->__invoke($showEnvelopeQuery);
    }

    private function generateEnvelope(
        string $title,
        string $currentBudget,
        string $targetBudget,
        string $uuid,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        $envelope = $this->createEnvelopeFactory->createFromDto(
            new CreateEnvelopeInput($title, $currentBudget, $targetBudget),
            $parentEnvelope,
            'test-user-uuid',
        );

        return $envelope->setUuid($uuid);
    }
}
