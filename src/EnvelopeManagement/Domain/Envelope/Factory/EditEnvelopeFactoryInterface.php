<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Factory;

use App\EnvelopeManagement\Application\Envelope\Dto\EditEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

interface EditEnvelopeFactoryInterface
{
    public function createFromDto(
        EnvelopeInterface $envelope,
        EditEnvelopeInputInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface;
}
