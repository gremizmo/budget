<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Dto;

final readonly class CreditEnvelopeInput implements CreditEnvelopeInputInterface
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
