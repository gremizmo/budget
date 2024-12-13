<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Presentation\HTTP\DTOs;

final readonly class CreateEnvelopeInput
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $targetBudget,
    ) {
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
