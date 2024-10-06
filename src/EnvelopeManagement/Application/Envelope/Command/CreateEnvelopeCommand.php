<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Command;

use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Command\CommandInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

readonly class CreateEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private CreateEnvelopeInputInterface $createEnvelopeDTO,
        private string $userUuid,
        private ?EnvelopeInterface $parentEnvelope = null,
    ) {
    }

    public function getCreateEnvelopeDTO(): CreateEnvelopeInputInterface
    {
        return $this->createEnvelopeDTO;
    }

    public function getParentEnvelope(): ?EnvelopeInterface
    {
        return $this->parentEnvelope;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}
