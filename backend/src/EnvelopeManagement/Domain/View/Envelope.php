<?php

namespace App\EnvelopeManagement\Domain\View;

readonly class Envelope implements EnvelopeInterface
{
    private string $uuid;
    private string $createdAt;
    private string $updatedAt;
    private string $currentBudget;
    private string $targetBudget;
    private string $name;
    private string $userUuid;

    private bool $isDeleted;

    private function __construct(
        string $uuid,
        string $createdAt,
        string $updatedAt,
        string $currentBudget,
        string $targetBudget,
        string $name,
        string $userUuid,
        bool $isDeleted,
    ) {
        $this->uuid = $uuid;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->currentBudget = $currentBudget;
        $this->targetBudget = $targetBudget;
        $this->name = $name;
        $this->userUuid = $userUuid;
        $this->isDeleted = $isDeleted;
    }

    public static function create(array $envelope): self
    {
        return new self(
            $envelope['uuid'],
            $envelope['created_at'],
            $envelope['updated_at'],
            $envelope['current_budget'],
            $envelope['target_budget'],
            $envelope['name'],
            $envelope['user_uuid'],
            $envelope['is_deleted'],
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getCurrentBudget(): string
    {
        return $this->currentBudget;
    }

    public function getTargetBudget(): string
    {
        return $this->targetBudget;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }
}
