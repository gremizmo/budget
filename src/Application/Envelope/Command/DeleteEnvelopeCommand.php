<?php

declare(strict_types=1);

namespace App\Application\Envelope\Command;

use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Shared\Command\CommandInterface;

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
