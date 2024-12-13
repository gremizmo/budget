<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Presentation\HTTP\DTOs;

final readonly class CreditEnvelopeInput
{
    public function __construct(
        public string $creditMoney,
    ) {
    }

    public function getCreditMoney(): string
    {
        return $this->creditMoney;
    }
}
