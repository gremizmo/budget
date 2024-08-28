<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Model\UserInterface;

interface CreateEnvelopeFactoryInterface
{
    public function createFromDto(
        CreateEnvelopeInputInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope,
        UserInterface $user,
    ): EnvelopeInterface;
}
