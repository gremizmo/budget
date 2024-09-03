<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Builder;

use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

interface CreateEnvelopeBuilderInterface
{
    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self;

    public function setCreateEnvelopeDto(CreateEnvelopeInputInterface $createEnvelopeDto): self;

    public function setUserUuid(string $userUuid): self;

    public function build(): EnvelopeInterface;
}
