<?php

declare(strict_types=1);

namespace App\Tests\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Query\QueryInterface;
use App\Infra\Http\Rest\Shared\Adapter\QueryBusAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Envelope;

class QueryBusAdapterTest extends TestCase
{
    public function testQuery(): void
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $query = $this->createMock(QueryInterface::class);
        $handledStamp = new HandledStamp('result', 'handler');
        $envelope = new Envelope($query, [$handledStamp]);

        $messageBus->expects($this->once())
            ->method('dispatch')
            ->with($query)
            ->willReturn($envelope);

        $adapter = new QueryBusAdapter($messageBus);
        $result = $adapter->query($query);

        $this->assertSame('result', $result);
    }
}
