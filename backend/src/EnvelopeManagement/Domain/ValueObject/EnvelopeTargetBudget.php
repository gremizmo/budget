<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObject;

use App\EnvelopeManagement\Domain\Exception\TargetBudgetException;

readonly class EnvelopeTargetBudget
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

    public function __toString(): string
    {
        return $this->targetBudget;
    }
}
