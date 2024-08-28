<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Dto\EditEnvelopeDtoInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;

interface EditEnvelopeFactoryInterface
{
    public function createFromDto(
        EnvelopeInterface $envelope,
        EditEnvelopeDtoInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface;
}
