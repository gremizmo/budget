<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

use App\EnvelopeManagement\Domain\Exceptions\CurrentBudgetException;
use Assert\Assert;

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

        Assert::that($currentBudget)
            ->notBlank('Current budget should not be blank.')
            ->string('Current budget must be a string.')
            ->minLength(1, 'The current budget must be at least 1 character long.')
            ->maxLength(13, 'The current budget must be at most 13 character long.')
            ->regex('/^\d+(\.\d{2})?$/', 'The current budget must be a string representing a number with up to two decimal places (e.g., "0.00").')
        ;
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
