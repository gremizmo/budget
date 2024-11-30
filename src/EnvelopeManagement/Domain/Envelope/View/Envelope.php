<?php

namespace App\EnvelopeManagement\Domain\Envelope\View;

readonly class Envelope implements EnvelopeInterface
{
    private string $uuid;
    private string $updatedAt;
    private string $currentBudget;
    private string $targetBudget;
    private string $title;
    private string $userUuid;
    private ?string $parentUuid;

    private function __construct(
        string $uuid,
        string $updatedAt,
        string $currentBudget,
        string $targetBudget,
        string $title,
        string $userUuid,
        ?string $parentUuid = null,
    ) {
        $this->uuid = $uuid;
        $this->updatedAt = $updatedAt;
        $this->currentBudget = $currentBudget;
        $this->targetBudget = $targetBudget;
        $this->title = $title;
        $this->userUuid = $userUuid;
        $this->parentUuid = $parentUuid;
    }

    public static function createFromQueryRepository(array $dataFromDatabase): self
    {
        return new self(
            $dataFromDatabase['uuid'],
            $dataFromDatabase['updated_at'],
            $dataFromDatabase['current_budget'],
            $dataFromDatabase['target_budget'],
            $dataFromDatabase['title'],
            $dataFromDatabase['user_uuid'],
            $dataFromDatabase['parent_uuid'] ?? null,
        );
    }
}
