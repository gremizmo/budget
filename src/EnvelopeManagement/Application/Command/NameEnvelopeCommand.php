<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Command;

use App\EnvelopeManagement\Domain\Command\CommandInterface;

readonly class NameEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private string $name,
        private string $uuid,
        private string $userUuid,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
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
