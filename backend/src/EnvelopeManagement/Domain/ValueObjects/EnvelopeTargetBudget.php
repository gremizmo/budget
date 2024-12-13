<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

use App\EnvelopeManagement\Domain\Exceptions\TargetBudgetException;

final readonly class EnvelopeTargetBudget
{
    private function __construct(protected string $targetBudget)
    {
        if (floatval($targetBudget) <= 0) {
            throw TargetBudgetException::isBelowZero();
        }
    }

    public static function create(string $targetBudget): self
    {
        return new self($targetBudget);
    }

    public function toString(): string
    {
        return $this->targetBudget;
    }
}
