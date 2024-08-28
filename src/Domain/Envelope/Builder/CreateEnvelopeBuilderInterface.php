<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Model\UserInterface;

interface CreateEnvelopeBuilderInterface
{
    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self;

    public function setCreateEnvelopeDto(CreateEnvelopeDtoInterface $createEnvelopeDto): self;

    public function setUser(UserInterface $user): self;

    public function build(): EnvelopeInterface;
}
