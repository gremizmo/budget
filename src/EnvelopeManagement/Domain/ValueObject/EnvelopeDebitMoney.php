<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObject;

readonly class EnvelopeDebitMoney
{
    private function __construct(protected string $debitMoney)
    {
    }

    public static function create(string $debitMoney): self
    {
        return new self($debitMoney);
    }

    public function __toString(): string
    {
        return $this->debitMoney;
    }
}
