<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Application\Envelope\Dto\EditEnvelopeInputInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;

interface EditEnvelopeFactoryInterface
{
    public function createFromDto(
        EnvelopeInterface $envelope,
        EditEnvelopeInputInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface;
}
