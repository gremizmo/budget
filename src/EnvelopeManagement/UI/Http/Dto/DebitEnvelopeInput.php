<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Dto;

final readonly class DebitEnvelopeInput implements DebitEnvelopeInputInterface
{
    public function __construct(
        public string $debitMoney,
    ) {
    }

    public function getDebitMoney(): string
    {
        return $this->debitMoney;
    }
}
