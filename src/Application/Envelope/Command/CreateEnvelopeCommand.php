<?php

declare(strict_types=1);

namespace App\Application\Envelope\Command;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Command\CommandInterface;
use App\Domain\Shared\Model\UserInterface;

readonly class CreateEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private CreateEnvelopeDtoInterface $createEnvelopeDTO,
        private UserInterface $user,
        private ?EnvelopeInterface $parentEnvelope = null,
    ) {
    }

    public function getCreateEnvelopeDTO(): CreateEnvelopeDtoInterface
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
