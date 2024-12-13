<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Presentation\HTTP\DTOs;

final readonly class DebitEnvelopeInput
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
