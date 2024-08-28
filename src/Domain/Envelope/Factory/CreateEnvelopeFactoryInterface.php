<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Model\UserInterface;

interface CreateEnvelopeFactoryInterface
{
    public function createFromDto(
        CreateEnvelopeDtoInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope,
        UserInterface $user,
    ): EnvelopeInterface;
}
