<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Aggregate;

use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;

interface EnvelopeInterface
{
    public static function create(
        string $envelopeId,
        string $userId,
        string $targetBudget,
        string $name,
        EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ): self;
}
