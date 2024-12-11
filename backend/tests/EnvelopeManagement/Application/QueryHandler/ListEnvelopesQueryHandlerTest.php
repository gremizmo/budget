<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\QueryHandler;

use App\EnvelopeManagement\Application\Query\ListEnvelopesQuery;
use App\EnvelopeManagement\Application\QueryHandler\ListEnvelopesQueryHandler;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\Envelope;
use App\EnvelopeManagement\Domain\View\EnvelopesPaginated;
use App\EnvelopeManagement\UI\Http\Dto\ListEnvelopesInput;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryHandlerTest extends TestCase
{
    private ListEnvelopesQueryHandler $listEnvelopesQueryHandler;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeQueryRepositoryInterface&MockObject $envelopeQueryRepository;

    protected function setUp(): void
    {
        $this->envelopeQueryRepository = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);

        $this->listEnvelopesQueryHandler = new ListEnvelopesQueryHandler(
            $this->envelopeQueryRepository,
        );
    }

    public function testListEnvelopesSuccess(): void
    {
        $envelopeView = Envelope::create(
            [
                'uuid' => 'be0c3a86-c3c9-467f-b675-3f519fd96111',
                'name' => 'Electricity',
                'target_budget' => '300.00',
                'current_budget' => '150.00',
                'user_uuid' => 'd26cc02e-99e7-428c-9d61-572dff3f84a7',
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'is_deleted' => false,
            ],
        );
        $envelopePaginated = new EnvelopesPaginated([$envelopeView], 1);
        $listEnvelopesInput = new ListEnvelopesInput([], 10, 0);
        $listEnvelopesQuery = new ListEnvelopesQuery(
            'd26cc02e-99e7-428c-9d61-572dff3f84a7',
            $listEnvelopesInput,
        );

        $this->envelopeQueryRepository->expects($this->once())->method('findBy')->willReturn($envelopePaginated);

        $envelopePaginatedResult = $this->listEnvelopesQueryHandler->__invoke($listEnvelopesQuery);

        $this->assertEquals($envelopePaginated, $envelopePaginatedResult);
    }
}
