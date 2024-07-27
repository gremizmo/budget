<?php

declare(strict_types=1);

namespace App\Tests\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Command\CommandInterface;
use App\Infra\Http\Rest\Shared\Adapter\CommandBusAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;

class CommandBusAdapterTest extends TestCase
{
    public function testExecute(): void
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $command = $this->createMock(CommandInterface::class);
        $envelope = new Envelope($command);

        $messageBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($envelope);

        $adapter = new CommandBusAdapter($messageBus);
        $adapter->execute($command);
    }
}
