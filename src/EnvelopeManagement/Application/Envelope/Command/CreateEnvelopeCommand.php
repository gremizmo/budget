<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Command;

use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Shared\Command\CommandInterface;

readonly class CreateEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private CreateEnvelopeInputInterface $createEnvelopeDTO,
        private int $userId,
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

    public function getUserId(): int
    {
        return $this->userId;
    }
}
