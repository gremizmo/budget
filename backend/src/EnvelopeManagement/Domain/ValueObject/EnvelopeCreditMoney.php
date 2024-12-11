<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObject;

readonly class EnvelopeCreditMoney
{
    private function __construct(protected string $creditMoney)
    {
    }

    public static function create(string $creditMoney): self
    {
        return new self($creditMoney);
    }

    public function __toString(): string
    {
        return $this->creditMoney;
    }
}
