<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Command;

use App\EnvelopeManagement\Application\Envelope\Dto\EditEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Command\CommandInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

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
