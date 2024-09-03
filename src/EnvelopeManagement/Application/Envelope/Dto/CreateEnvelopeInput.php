<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Dto;

final readonly class CreateEnvelopeInput implements CreateEnvelopeInputInterface
{
    public function __construct(
        public string $title,
        public string $currentBudget,
        public string $targetBudget,
        public ?string $parentUuid = null,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCurrentBudget(): string
    {
        return $this->currentBudget;
    }

    public function getTargetBudget(): string
    {
        return $this->targetBudget;
    }

    public function getParentUuid(): ?string
    {
        return $this->parentUuid;
    }
}
