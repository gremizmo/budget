<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Dto;

interface CreditEnvelopeInputInterface
{
    public function getCreditMoney(): string;
}
