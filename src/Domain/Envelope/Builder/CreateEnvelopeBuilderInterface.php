<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Model\UserInterface;

interface CreateEnvelopeBuilderInterface
{
    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self;

    public function setCreateEnvelopeDto(CreateEnvelopeInputInterface $createEnvelopeDto): self;

    public function setUser(UserInterface $user): self;

    public function build(): EnvelopeInterface;
}
