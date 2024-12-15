<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

use App\EnvelopeManagement\Domain\Exceptions\TargetBudgetException;
use Assert\Assert;

final readonly class EnvelopeTargetBudget
{
    private function __construct(protected string $targetBudget)
    {
        if (floatval($targetBudget) <= 0) {
            throw TargetBudgetException::isBelowZero();
        }

        Assert::that($targetBudget)
            ->notBlank('Target budget should not be blank.')
            ->string('The target budget must be a string.')
            ->minLength(1, 'The target budget must be at least 1 character long.')
            ->maxLength(13, 'The target budget must be at most 13 character long.')
            ->regex('/^\d+(\.\d{2})?$/', 'The target budget must be a string representing a number with up to two decimal places (e.g., "0.00").')
        ;
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
