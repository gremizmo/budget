<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\User\Entity\UserInterface;

interface CreateEnvelopeFactoryInterface
{
    public function createFromDto(
        CreateEnvelopeDtoInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope,
        UserInterface $user,
    ): EnvelopeInterface;
}
