<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObject;

readonly class EnvelopeTargetBudget
{
    private function __construct(protected string $targetBudget)
    {
    }

    public static function withTargetBudget(string $targetBudget): self
    {
        return new self($targetBudget);
    }

    public function __toString(): string
    {
        return $this->targetBudget;
    }
}
