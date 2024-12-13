<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

final readonly class EnvelopeCreditMoney
{
    private function __construct(protected string $creditMoney)
    {
    }

    public static function create(string $creditMoney): self
    {
        return new self($creditMoney);
    }

    public function toString(): string
    {
        return $this->creditMoney;
    }
}
