<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Dto;

interface DebitEnvelopeInputInterface
{
    public function getDebitMoney(): string;
}
