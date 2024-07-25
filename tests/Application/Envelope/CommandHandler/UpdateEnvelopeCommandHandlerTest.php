<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\UpdateEnvelopeCommand;
use App\Application\Envelope\CommandHandler\UpdateEnvelopeCommandHandler;
use App\Domain\Envelope\Dto\UpdateEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Factory\EnvelopeFactory;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateEnvelopeCommandHandlerTest extends TestCase
{
    private MockObject&EnvelopeCommandRepositoryInterface $envelopeRepositoryMock;
    private MockObject&LoggerInterface $loggerMock;
    private EnvelopeFactory $envelopeFactory;
    private UpdateEnvelopeCommandHandler $updateEnvelopeCommandHandler;

    protected function setUp(): void
    {
        $this->envelopeRepositoryMock = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->envelopeFactory = new EnvelopeFactory();
        $this->updateEnvelopeCommandHandler = new UpdateEnvelopeCommandHandler(
            $this->envelopeRepositoryMock,
            $this->envelopeFactory,
            $this->loggerMock
        );
    }

    public function testInvokeSuccess(): void
    {
        $envelope = new Envelope();
        $updateEnvelopeDto = new UpdateEnvelopeDto('Updated Title', '150.0', '250.0', null);
        $updateEnvelopeCommand = new UpdateEnvelopeCommand($envelope, $updateEnvelopeDto);

        $this->envelopeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->equalTo($envelope));

        $this->loggerMock->expects($this->never())
            ->method('error');

        $this->updateEnvelopeCommandHandler->__invoke($updateEnvelopeCommand);
    }

    public function testInvokeException(): void
    {
        $exception = new \Exception('Test Exception');
        $envelope = new Envelope();
        $updateEnvelopeDto = new UpdateEnvelopeDto('Updated Title', '150.0', '250.0', null);
        $updateEnvelopeCommand = new UpdateEnvelopeCommand($envelope, $updateEnvelopeDto);

        $this->envelopeRepositoryMock->method('save')
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with($this->equalTo('Test Exception'));

        $this->expectException(\Exception::class);

        $this->updateEnvelopeCommandHandler->__invoke($updateEnvelopeCommand);
    }
}
