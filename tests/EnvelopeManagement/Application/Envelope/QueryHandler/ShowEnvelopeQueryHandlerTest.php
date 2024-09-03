<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Envelope\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Application\Envelope\QueryHandler\ShowEnvelopeQueryHandler;
use App\EnvelopeManagement\Application\Envelope\QueryHandler\ShowEnvelopeQueryHandlerException;
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

        $this->createEnvelopeFactory = new CreateEnvelopeFactory($this->logger, new CreateEnvelopeBuilder(
            new CreateEnvelopeTargetBudgetValidator(),
            new CreateEnvelopeCurrentBudgetValidator(),
            new CreateEnvelopeTitleValidator($this->queryBus),
            $this->uuidAdapter,
            $this->logger,
            Envelope::class,
        ));

        $this->showEnvelopeQueryHandler = new ShowEnvelopeQueryHandler(
            $this->envelopeQueryRepository,
            $this->logger,
        );
    }

    /**
     * @throws ShowEnvelopeQueryHandlerException
     * @throws CreateEnvelopeFactoryException
     */
    public function testShowEnvelopeSuccess(): void
    {
        $envelopeToShow = $this->generateEnvelope('Electricity', '80.00', '80.00', 'test-envelope-uuid');
        $showEnvelopeQuery = new ShowEnvelopeQuery($envelopeToShow->getUuid(), 'test-user-uuid');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willReturn($envelopeToShow);

        $envelope = $this->showEnvelopeQueryHandler->__invoke($showEnvelopeQuery);

        $this->assertEquals($envelopeToShow, $envelope);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function testShowEnvelopeFailure(): void
    {
        $envelopeToShow = $this->generateEnvelope('Electricity', '80.00', '80.00', 'test-envelope-uuid');
        $showEnvelopeQuery = new ShowEnvelopeQuery($envelopeToShow->getUuid(), 'test-user-uuid');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willThrowException(new EnvelopeQueryRepositoryException(EnvelopeQueryRepositoryException::MESSAGE, 400));
        $this->expectException(ShowEnvelopeQueryHandlerException::class);
        $this->expectExceptionMessage('An error occurred while getting an envelope in ShowEnvelopeQueryHandler');

        $this->showEnvelopeQueryHandler->__invoke($showEnvelopeQuery);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function testShowEnvelopeReturnsNull(): void
    {
        $envelopeToShow = $this->generateEnvelope('Electricity', '80.00', '80.00', 'test-envelope-uuid');
        $showEnvelopeQuery = new ShowEnvelopeQuery($envelopeToShow->getUuid(), 'test-user-uuid');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willReturn(null);
        $this->expectException(ShowEnvelopeQueryHandlerException::class);
        $this->expectExceptionMessage('An error occurred while getting an envelope in ShowEnvelopeQueryHandler');

        $this->showEnvelopeQueryHandler->__invoke($showEnvelopeQuery);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
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
