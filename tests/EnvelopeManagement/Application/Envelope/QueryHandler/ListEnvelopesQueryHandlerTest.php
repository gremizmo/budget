<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Envelope\Dto\ListEnvelopesInput;
use App\EnvelopeManagement\Application\Envelope\Query\ListEnvelopesQuery;
use App\EnvelopeManagement\Application\Envelope\QueryHandler\ListEnvelopesQueryHandler;
use App\EnvelopeManagement\Application\Envelope\QueryHandler\ListEnvelopesQueryHandlerException;
use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactoryException;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopesPaginated;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope;
use App\EnvelopeManagement\Infrastructure\Envelope\Repository\EnvelopeQueryRepositoryException;
use App\EnvelopeManagement\Infrastructure\Shared\Adapter\LoggerAdapter;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryHandlerTest extends TestCase
{
    private ListEnvelopesQueryHandler $listEnvelopesQueryHandler;
    private LoggerInterface $logger;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeQueryRepositoryInterface&MockObject $envelopeQueryRepository;
    private CreateEnvelopeFactory $createEnvelopeFactory;

    protected function setUp(): void
    {
        $this->envelopeQueryRepository = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->logger = new LoggerAdapter($this->createMock(PsrLoggerInterface::class));

        $this->createEnvelopeFactory = new CreateEnvelopeFactory($this->logger, new CreateEnvelopeBuilder(
            new CreateEnvelopeTargetBudgetValidator(),
            new CreateEnvelopeCurrentBudgetValidator(),
            new CreateEnvelopeTitleValidator($this->queryBus),
            $this->logger,
            Envelope::class,
        ));

        $this->listEnvelopesQueryHandler = new ListEnvelopesQueryHandler(
            $this->envelopeQueryRepository,
            $this->logger,
        );
    }

    /**
     * @throws ListEnvelopesQueryHandlerException
     * @throws CreateEnvelopeFactoryException
     */
    public function testListEnvelopesSuccess(): void
    {
        $envelopePaginated = new EnvelopesPaginated([$this->generateEnvelope('Electricity', '80.00', '80.00', 2)], 1);
        $listEnvelopesInput = new ListEnvelopesInput([],10, 0);
        $listEnvelopesQuery = new ListEnvelopesQuery(1, $listEnvelopesInput);

        $this->envelopeQueryRepository->expects($this->once())->method('findBy')->willReturn($envelopePaginated);

        $envelopePaginatedResult = $this->listEnvelopesQueryHandler->__invoke($listEnvelopesQuery);

        $this->assertEquals($envelopePaginated, $envelopePaginatedResult);
    }

    public function testListEnvelopesFailure(): void
    {
        $listEnvelopesInput = new ListEnvelopesInput([],10, 0);
        $listEnvelopesQuery = new ListEnvelopesQuery(1, $listEnvelopesInput);

        $this->envelopeQueryRepository->expects($this->once())->method('findBy')->willThrowException(new EnvelopeQueryRepositoryException(EnvelopeQueryRepositoryException::MESSAGE, 400));
        $this->expectException(ListEnvelopesQueryHandlerException::class);
        $this->expectExceptionMessage('An error occurred while getting envelopes in ListEnvelopesQueryHandler');

        $this->listEnvelopesQueryHandler->__invoke($listEnvelopesQuery);
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
