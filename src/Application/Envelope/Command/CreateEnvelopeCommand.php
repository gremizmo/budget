<?php

declare(strict_types=1);

namespace App\Application\Envelope\Command;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Shared\Command\CommandInterface;

readonly class CreateEnvelopeCommand implements CommandInterface
{
    public function __construct(private CreateEnvelopeDtoInterface $createEnvelopeDTO, private ?EnvelopeInterface $parentEnvelope = null)
    {
    }

    public function getCreateEnvelopeDTO(): CreateEnvelopeDtoInterface
    {
        return $this->createEnvelopeDTO;
    }

    public function getParentEnvelope(): ?EnvelopeInterface
    {
        return $this->parentEnvelope;
    }
}
