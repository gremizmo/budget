<?php

declare(strict_types=1);

namespace App\Application\Envelope\Command;

use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Shared\Command\CommandInterface;

readonly class EditEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private EnvelopeInterface $envelope,
        private UpdateEnvelopeDtoInterface $updateEnvelopeDTO,
        private ?EnvelopeInterface $parentEnvelope = null,
    ) {
    }

    public function getEnvelope(): EnvelopeInterface
    {
        return $this->envelope;
    }

    public function getUpdateEnvelopeDTO(): UpdateEnvelopeDtoInterface
    {
        return $this->updateEnvelopeDTO;
    }

    public function getParentEnvelope(): ?EnvelopeInterface
    {
        return $this->parentEnvelope;
    }
}
