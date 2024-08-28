<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Command;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Infra\Http\Rest\Envelope\Entity\Envelope;
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
