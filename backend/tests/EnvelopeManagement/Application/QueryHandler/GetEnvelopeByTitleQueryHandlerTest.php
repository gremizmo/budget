<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\QueryHandler;

use App\EnvelopeManagement\Application\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Application\QueryHandler\GetEnvelopeByTitleQueryHandler;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\Envelope;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetEnvelopeByTitleQueryHandlerTest extends TestCase
{
    private GetEnvelopeByTitleQueryHandler $getEnvelopeByTitleQueryHandler;
    private EnvelopeQueryRepositoryInterface&MockObject $envelopeQueryRepository;

    protected function setUp(): void
    {
        $this->envelopeQueryRepository = $this->createMock(EnvelopeQueryRepositoryInterface::class);
        $this->getEnvelopeByTitleQueryHandler = new GetEnvelopeByTitleQueryHandler(
            $this->envelopeQueryRepository,
        );
    }

    public function testGetEnvelopeByTitleSuccess(): void
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
        $getEnvelopeByTitleQuery = new GetEnvelopeByTitleQuery('Electricity', 'test-uuid');

        $this->envelopeQueryRepository->expects($this->once())->method('findOneBy')->willReturn($envelopeView);

        $envelope = $this->getEnvelopeByTitleQueryHandler->__invoke($getEnvelopeByTitleQuery);

        $this->assertEquals($envelopeView, $envelope);
    }
}
