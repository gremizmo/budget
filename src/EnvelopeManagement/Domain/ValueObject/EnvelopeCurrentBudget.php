<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObject;

readonly class EnvelopeCurrentBudget
{
    private function __construct(protected string $currentBudget)
    {
    }

    public static function withCurrentBudget(string $currentBudget): self
    {
        return new self($currentBudget);
    }

    public function __toString(): string
    {
        return $this->currentBudget;
    }
}
