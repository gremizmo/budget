<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Dto;

interface CreditEnvelopeInputInterface
{
    public function getCreditMoney(): string;
}
