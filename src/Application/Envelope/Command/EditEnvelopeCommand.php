<?php

declare(strict_types=1);

namespace App\Application\Envelope\Command;

use App\Application\Envelope\Dto\EditEnvelopeInputInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Command\CommandInterface;

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
