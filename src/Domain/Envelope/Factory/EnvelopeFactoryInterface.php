<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;

interface EnvelopeFactoryInterface
{
    public function createEnvelope(
        CreateEnvelopeDtoInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope
    ): EnvelopeInterface;

    public function updateEnvelope(
        EnvelopeInterface $envelope,
        UpdateEnvelopeDtoInterface $updateEnvelopeDto,
    ): EnvelopeInterface;
}
