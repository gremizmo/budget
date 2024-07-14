<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Application\Envelope\CommandHandler\CreateEnvelopeCommandHandler;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Factory\EnvelopeFactory;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Infra\Http\Rest\Envelope\Dto\CreateEnvelopeDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeCommandHandlerTest extends TestCase
{
    private MockObject&EnvelopeCommandRepositoryInterface $envelopeRepositoryMock;
    private MockObject&LoggerInterface $loggerMock;
    private EnvelopeFactory $envelopeFactory;
    private CreateEnvelopeCommandHandler $createEnvelopeCommandHandler;
    private CreateEnvelopeCommand $createEnvelopeCommand;

    protected function setUp(): void
    {
        $this->envelopeRepositoryMock = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->envelopeFactory = new EnvelopeFactory();
        $this->createEnvelopeCommandHandler = new CreateEnvelopeCommandHandler(
            $this->envelopeRepositoryMock,
            $this->envelopeFactory,
            $this->loggerMock
        );
        $this->createEnvelopeCommand = new CreateEnvelopeCommand(
            new CreateEnvelopeDto(
                'Test Title',
                '100.0',
                '200.0',
                null,
            )
        );
    }

    public function testInvokeSuccess(): void
    {
        $expectedEnvelope = (new Envelope())
            ->setTitle('Test Title')
            ->setCurrentBudget('100.0')
            ->setTargetBudget('200.0')
            ->setParent(null);

        $this->envelopeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->callback(function (EnvelopeInterface $envelope) use ($expectedEnvelope) {
                return $envelope->getTitle() === $expectedEnvelope->getTitle()
                    && $envelope->getCurrentBudget() === $expectedEnvelope->getCurrentBudget()
                    && $envelope->getTargetBudget() === $expectedEnvelope->getTargetBudget()
                    && $envelope->getParent() === $expectedEnvelope->getParent();
            }));

        $this->loggerMock->expects($this->never())
            ->method('error');

        $this->createEnvelopeCommandHandler->__invoke($this->createEnvelopeCommand);
    }

    public function testInvokeException(): void
    {
        $exception = new \Exception('Test Exception');
        $this->envelopeRepositoryMock->method('save')
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with($this->equalTo('Test Exception'));

        $this->expectException(\Exception::class);

        $this->createEnvelopeCommandHandler->__invoke($this->createEnvelopeCommand);
    }
}
