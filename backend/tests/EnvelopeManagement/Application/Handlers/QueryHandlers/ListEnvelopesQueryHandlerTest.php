<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Handlers\QueryHandlers;

use App\EnvelopeManagement\Application\Handlers\QueryHandlers\ListEnvelopesQueryHandler;
use App\EnvelopeManagement\Application\Queries\ListEnvelopesQuery;
use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\Domain\Ports\Outbound\QueryBusInterface;
use App\EnvelopeManagement\Presentation\HTTP\DTOs\ListEnvelopesInput;
use App\EnvelopeManagement\ReadModels\Views\EnvelopesPaginated;
use App\EnvelopeManagement\ReadModels\Views\EnvelopeView;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryHandlerTest extends TestCase
{
    private ListEnvelopesQueryHandler $listEnvelopesQueryHandler;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeRepositoryInterface&MockObject $envelopeRepository;

    #[\Override]
    protected function setUp(): void
    {
        $this->envelopeRepository = $this->createMock(EnvelopeRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);

        $this->listEnvelopesQueryHandler = new ListEnvelopesQueryHandler(
            $this->envelopeRepository,
        );
    }

    public function testListEnvelopesSuccess(): void
    {
        $envelopeView = EnvelopeView::createFromRepository(
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
            $listEnvelopesInput->getOrderBy(),
            $listEnvelopesInput->getLimit(),
            $listEnvelopesInput->getOffset(),
        );

        $this->envelopeRepository->expects($this->once())->method('findBy')->willReturn($envelopePaginated);

        $envelopePaginatedResult = $this->listEnvelopesQueryHandler->__invoke($listEnvelopesQuery);

        $this->assertEquals($envelopePaginated, $envelopePaginatedResult);
    }
}
