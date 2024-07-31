<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Command;

use App\Application\Envelope\Command\EditEnvelopeCommand;
use App\Domain\Envelope\Dto\EditEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use PHPUnit\Framework\TestCase;

class UpdateEnvelopeCommandTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $envelope = new Envelope();
        $updateEnvelopeDTO = new EditEnvelopeDto('Title', '100.0', '200.0', null);
        $command = new EditEnvelopeCommand($envelope, $updateEnvelopeDTO);

        $this->assertSame($envelope, $command->getEnvelope());
        $this->assertSame($updateEnvelopeDTO, $command->getUpdateEnvelopeDTO());
    }
}
