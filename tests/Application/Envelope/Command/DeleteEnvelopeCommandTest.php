<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Command;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Domain\Envelope\Entity\Envelope;
use PHPUnit\Framework\TestCase;

class DeleteEnvelopeCommandTest extends TestCase
{
    public function testConstructorAndGetter(): void
    {
        $envelope = new Envelope();
        $command  = new DeleteEnvelopeCommand($envelope);

        $this->assertSame($envelope, $command->getEnvelope());
    }
}
