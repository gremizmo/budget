<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Application\Envelope\CommandHandler\DeleteEnvelopeCommandHandler;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteEnvelopeCommandHandlerTest extends TestCase
{
    private MockObject&EnvelopeCommandRepositoryInterface $envelopeRepositoryMock;
    private MockObject&LoggerInterface $loggerMock;
    private DeleteEnvelopeCommandHandler $deleteEnvelopeCommandHandler;

    protected function setUp(): void
    {
        $this->envelopeRepositoryMock = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->deleteEnvelopeCommandHandler = new DeleteEnvelopeCommandHandler(
            $this->envelopeRepositoryMock,
            $this->loggerMock
        );
    }

    public function testInvokeSuccess(): void
    {
        $envelope = new Envelope();
        $deleteEnvelopeCommand = new DeleteEnvelopeCommand($envelope);

        $this->envelopeRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($envelope));

        $this->loggerMock->expects($this->never())
            ->method('error');

        $this->deleteEnvelopeCommandHandler->__invoke($deleteEnvelopeCommand);
    }

    public function testInvokeException(): void
    {
        $exception = new \Exception('Test Exception');
        $envelope = new Envelope();
        $deleteEnvelopeCommand = new DeleteEnvelopeCommand($envelope);

        $this->envelopeRepositoryMock->method('delete')
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with($this->equalTo('Test Exception'));

        $this->expectException(\Exception::class);

        $this->deleteEnvelopeCommandHandler->__invoke($deleteEnvelopeCommand);
    }
}
