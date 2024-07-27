<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Application\Envelope\CommandHandler\DeleteEnvelopeCommandHandler;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteEnvelopeCommandHandlerTest extends TestCase
{
    private MockObject&EnvelopeCommandRepositoryInterface $envelopeRepositoryMock;
    private DeleteEnvelopeCommandHandler $deleteEnvelopeCommandHandler;

    protected function setUp(): void
    {
        $this->envelopeRepositoryMock = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->deleteEnvelopeCommandHandler = new DeleteEnvelopeCommandHandler($this->envelopeRepositoryMock);
    }

    public function testInvoke(): void
    {
        $deleteEnvelopeCommand = new DeleteEnvelopeCommand(new Envelope());

        $this->envelopeRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->isInstanceOf(Envelope::class));

        $this->deleteEnvelopeCommandHandler->__invoke($deleteEnvelopeCommand);
    }
}
