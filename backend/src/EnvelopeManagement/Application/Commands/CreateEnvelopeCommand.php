<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Commands;

use App\EnvelopeManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class CreateEnvelopeCommand implements CommandInterface
{
    public function __construct(
        private string $uuid,
        private string $userUuid,
        private string $name,
        private string $targetBudget,
    ) {
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTargetBudget(): string
    {
        return $this->targetBudget;
    }
}
