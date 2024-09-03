<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Application\Envelope\QueryHandler\GetEnvelopeByTitleQueryHandler;
use App\EnvelopeManagement\Application\Envelope\QueryHandler\GetEnvelopeByTitleQueryHandlerException;
use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactoryException;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use App\EnvelopeManagement\Infrastructure\Envelope\Repository\EnvelopeQueryRepositoryException;
use App\EnvelopeManagement\Infrastructure\Shared\Adapter\LoggerAdapter;
use App\EnvelopeManagement\Infrastructure\Shared\Adapter\UuidAdapter;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetEnvelopeByTitleQueryHandlerTest extends TestCase
{
    private GetEnvelopeByTitleQueryHandler $getEnvelopeByTitleQueryHandler;
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

        $this->createEnvelopeFactory = new CreateEnvelopeFactory($this->logger, new CreateEnvelopeBuilder(
            new CreateEnvelopeTargetBudgetValidator(),
            new CreateEnvelopeCurrentBudgetValidator(),
            new CreateEnvelopeTitleValidator($this->queryBus),
            $this->uuidAdapter,
            $this->logger,
            Envelope::class,
        ));

        $this->getEnvelopeByTitleQueryHandler = new GetEnvelopeByTitleQueryHandler(
            $this->envelopeQueryRepository,
            $this->logger,
        );
    }

    /**
     * @throws GetEnvelopeByTitleQueryHandlerException
     * @throws CreateEnvelopeFactoryException
     */
    public function testGetEnvelopeByTitleSuccess(): void
    {
        $envelopeToShow = $this->generateEnvelope('Electricity', '80.00', '80.00');
        $getEnvelopeByTitleQuery = new GetEnvelopeByTitleQuery('Electricity', 'test-uuid');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willReturn($envelopeToShow);

        $envelope = $this->getEnvelopeByTitleQueryHandler->__invoke($getEnvelopeByTitleQuery);

        $this->assertEquals($envelopeToShow, $envelope);
    }

    public function testGetEnvelopeByTitleFailure(): void
    {
        $getEnvelopeByTitleQuery = new GetEnvelopeByTitleQuery('Electricity', 'test-uuid');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willThrowException(new EnvelopeQueryRepositoryException(EnvelopeQueryRepositoryException::MESSAGE, 400));
        $this->expectException(GetEnvelopeByTitleQueryHandlerException::class);
        $this->expectExceptionMessage('An error occurred while getting an envelope by title in GetEnvelopeByTitleQueryHandler');

        $this->getEnvelopeByTitleQueryHandler->__invoke($getEnvelopeByTitleQuery);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
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
