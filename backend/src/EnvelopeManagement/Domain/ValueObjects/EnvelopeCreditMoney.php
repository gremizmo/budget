<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

use Assert\Assert;

final readonly class EnvelopeCreditMoney
{
    private function __construct(protected string $creditMoney)
    {
        Assert::that($creditMoney)
            ->notBlank('Credit money should not be blank.')
            ->string('Credit money must be a string.')
            ->minLength(1, 'The credit money must be at least 1 character long.')
            ->maxLength(13, 'The credit money must be at least 13 character long.')
            ->regex('/^\d+(\.\d{2})?$/', 'The credit money must be a string representing a number with up to two decimal places (e.g., "0.00").')
        ;
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
