<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\QueryHandler;

use App\EnvelopeManagement\Application\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Application\QueryHandler\EnvelopeNotFoundException;
use App\EnvelopeManagement\Application\QueryHandler\ShowEnvelopeQueryHandler;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\Envelope;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShowEnvelopeQueryHandlerTest extends TestCase
{
    private ShowEnvelopeQueryHandler $showEnvelopeQueryHandler;
    private QueryBusInterface&MockObject $queryBus;
    private EnvelopeQueryRepositoryInterface&MockObject $envelopeQueryRepository;

    protected function setUp(): void
    {
        $this->envelopeQueryRepository = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBusInterface::class);

        $this->showEnvelopeQueryHandler = new ShowEnvelopeQueryHandler(
            $this->envelopeQueryRepository,
        );
    }

    public function testShowEnvelopeSuccess(): void
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
        $showEnvelopeQuery = new ShowEnvelopeQuery($envelopeView->getUuid(), 'd26cc02e-99e7-428c-9d61-572dff3f84a7');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willReturn($envelopeView);

        $envelope = $this->showEnvelopeQueryHandler->__invoke($showEnvelopeQuery);

        $this->assertEquals($envelopeView, $envelope);
    }

    public function testShowEnvelopeReturnsNull(): void
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
        $showEnvelopeQuery = new ShowEnvelopeQuery($envelopeView->getUuid(), 'd26cc02e-99e7-428c-9d61-572dff3f84a7');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willReturn(null);
        $this->expectException(EnvelopeNotFoundException::class);
        $this->expectExceptionMessage('Envelope not found');

        $this->showEnvelopeQueryHandler->__invoke($showEnvelopeQuery);
    }
}
