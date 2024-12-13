<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Commands;

use App\EnvelopeManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class CreditEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private string $creditMoney,
        private string $uuid,
        private string $userUuid,
    ) {
    }

    public function getCreditMoney(): string
    {
        return $this->creditMoney;
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
