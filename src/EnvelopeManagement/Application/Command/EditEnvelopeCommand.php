<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Command;

use App\EnvelopeManagement\Application\Dto\EditEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Command\CommandInterface;

readonly class EditEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private EnvelopeInterface $envelope,
        private EditEnvelopeInputInterface $updateEnvelopeDTO,
        private ?EnvelopeInterface $parentEnvelope = null,
    ) {
    }

    public function getEnvelope(): EnvelopeInterface
    {
        return $this->envelope;
    }

    public function getUpdateEnvelopeDTO(): EditEnvelopeInputInterface
    {
        return $this->updateEnvelopeDTO;
    }

    public function getParentEnvelope(): ?EnvelopeInterface
    {
        return $this->parentEnvelope;
    }
}
