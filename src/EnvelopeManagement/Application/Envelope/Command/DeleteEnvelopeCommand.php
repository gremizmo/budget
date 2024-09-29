<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Command;

use App\EnvelopeManagement\Domain\Envelope\Command\CommandInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

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
