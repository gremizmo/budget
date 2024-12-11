<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Dto;

interface DebitEnvelopeInputInterface
{
    public function getDebitMoney(): string;
}
