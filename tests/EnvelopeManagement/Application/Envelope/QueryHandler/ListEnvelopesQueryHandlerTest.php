<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Application\Dto\ListEnvelopesInput;
use App\EnvelopeManagement\Application\Query\ListEnvelopesQuery;
use App\EnvelopeManagement\Application\QueryHandler\ListEnvelopesQueryHandler;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Factory\CreateEnvelopeFactory;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Domain\View\EnvelopesPaginated;
use App\EnvelopeManagement\Infrastructure\Adapter\UuidAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryHandlerTest extends TestCase
{
    private ListEnvelopesQueryHandler $listEnvelopesQueryHandler;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeQueryRepositoryInterface&MockObject $envelopeQueryRepository;
    private CreateEnvelopeFactory $createEnvelopeFactory;
    private UuidAdapter $uuidAdapter;

    protected function setUp(): void
    {
        $this->envelopeQueryRepository = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->uuidAdapter = new UuidAdapter();

        $this->createEnvelopeFactory = new CreateEnvelopeFactory(new CreateEnvelopeBuilder(
            new CreateEnvelopeTargetBudgetValidator(),
            new CreateEnvelopeCurrentBudgetValidator(),
            new CreateEnvelopeTitleValidator($this->queryBus),
            $this->uuidAdapter,
            Envelope::class,
        ));

        $this->listEnvelopesQueryHandler = new ListEnvelopesQueryHandler(
            $this->envelopeQueryRepository,
        );
    }

    public function testListEnvelopesSuccess(): void
    {
        $envelopePaginated = new EnvelopesPaginated([$this->generateEnvelope('Electricity', '80.00', '80.00')], 1);
        $listEnvelopesInput = new ListEnvelopesInput([], 10, 0);
        $listEnvelopesQuery = new ListEnvelopesQuery('test-uuid', $listEnvelopesInput);

        $this->envelopeQueryRepository->expects($this->once())->method('findBy')->willReturn($envelopePaginated);

        $envelopePaginatedResult = $this->listEnvelopesQueryHandler->__invoke($listEnvelopesQuery);

        $this->assertEquals($envelopePaginated, $envelopePaginatedResult);
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
