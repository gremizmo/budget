<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Command;

use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Shared\Command\CommandInterface;

readonly class DeleteEnvelopeCommand implements CommandInterface
{
    public function __construct(private EnvelopeInterface $envelope)
    {
    }

    public function getEnvelope(): EnvelopeInterface
    {
        return $this->envelope;
    }
}
