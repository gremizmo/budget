<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Commands;

use App\EnvelopeManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class DebitEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private string $debitMoney,
        private string $uuid,
        private string $userUuid,
    ) {
    }

    public function getDebitMoney(): string
    {
        return $this->debitMoney;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
