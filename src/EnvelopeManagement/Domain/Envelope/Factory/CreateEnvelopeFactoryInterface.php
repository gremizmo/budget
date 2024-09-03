<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Factory;

use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

interface CreateEnvelopeFactoryInterface
{
    public function createFromDto(
        CreateEnvelopeInputInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope,
        string $userUuid,
    ): EnvelopeInterface;
}
