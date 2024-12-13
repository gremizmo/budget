<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

use App\EnvelopeManagement\Domain\Exceptions\CurrentBudgetException;

final readonly class EnvelopeCurrentBudget
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

    public function toString(): string
    {
        return $this->currentBudget;
    }
}
