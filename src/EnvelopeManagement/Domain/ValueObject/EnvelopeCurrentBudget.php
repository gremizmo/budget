<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObject;

use App\EnvelopeManagement\Domain\Exception\CurrentBudgetException;

readonly class EnvelopeCurrentBudget
{
    private function __construct(protected string $currentBudget, protected string $targetBudget)
    {
        $currentBudgetFloat = floatval($currentBudget);

        if ($currentBudgetFloat < 0.00) {
            throw CurrentBudgetException::exceedsDebitLimit();
        }

        if ($currentBudgetFloat > floatval($targetBudget)) {
            throw CurrentBudgetException::exceedsCreditLimit();
        }
    }

    public static function create(string $currentBudget, string $targetBudget): self
    {
        return new self($currentBudget, $targetBudget);
    }

    public function __toString(): string
    {
        return $this->currentBudget;
    }
}
