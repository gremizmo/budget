<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Command;

use App\EnvelopeManagement\Domain\Command\CommandInterface;

readonly class CreateEnvelopeCommand implements CommandInterface
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
