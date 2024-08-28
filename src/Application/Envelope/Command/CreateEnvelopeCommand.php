<?php

declare(strict_types=1);

namespace App\Application\Envelope\Command;

use App\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Command\CommandInterface;
use App\Domain\Shared\Model\UserInterface;

readonly class CreateEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private CreateEnvelopeInputInterface $createEnvelopeDTO,
        private UserInterface $user,
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

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
