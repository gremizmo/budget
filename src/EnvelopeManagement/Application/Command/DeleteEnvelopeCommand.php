<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Command;

use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Command\CommandInterface;

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
