<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Command;

use App\BudgetManagement\Application\Envelope\Command\EditEnvelopeCommand;
use App\BudgetManagement\Application\Envelope\Dto\EditEnvelopeInput;
use App\BudgetManagement\Infrastructure\Http\Rest\Envelope\Entity\Envelope;
use PHPUnit\Framework\TestCase;

class UpdateEnvelopeCommandTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $envelope = new Envelope();
        $updateEnvelopeDTO = new EditEnvelopeInput('Title', '100.0', '200.0', null);
        $command = new EditEnvelopeCommand($envelope, $updateEnvelopeDTO);

        $this->assertSame($envelope, $command->getEnvelope());
        $this->assertSame($updateEnvelopeDTO, $command->getUpdateEnvelopeDTO());
    }
}
