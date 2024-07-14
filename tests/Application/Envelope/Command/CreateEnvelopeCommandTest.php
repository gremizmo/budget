<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Command;

use App\Application\Envelope\Command\UpdateEnvelopeCommand;
use App\Domain\Envelope\Entity\Envelope;
use App\Infra\Http\Rest\Envelope\Dto\UpdateEnvelopeDto;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeCommandTest extends TestCase
{
    public function testConstructorAndGetter(): void
    {
        $envelope = new Envelope();
        $updateEnvelopeDTO = new UpdateEnvelopeDto('Title', '100.0', '200.0', null);
        $command = new UpdateEnvelopeCommand($envelope, $updateEnvelopeDTO);

        $this->assertSame($envelope, $command->getEnvelope());
        $this->assertSame($updateEnvelopeDTO, $command->getUpdateEnvelopeDTO());
    }
}
