<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;

interface EditEnvelopeFactoryInterface
{
    public function createFromDto(
        EnvelopeInterface $envelope,
        UpdateEnvelopeDtoInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface;
}
