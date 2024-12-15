<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

use Assert\Assert;

final readonly class EnvelopeDebitMoney
{
    private function __construct(protected string $debitMoney)
    {
        Assert::that($debitMoney)
            ->notBlank('Debit money should not be blank.')
            ->string('Debit money must be a string.')
            ->minLength(1, 'The debit money must be at least 1 character long.')
            ->maxLength(13, 'The debit money must be at most 13 character long.')
            ->regex('/^\d+(\.\d{2})?$/')
        ;
    }

    public static function create(string $debitMoney): self
    {
        return new self($debitMoney);
    }

    public function toString(): string
    {
        return $this->debitMoney;
    }
}
