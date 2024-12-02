<?php

namespace App\EnvelopeManagement\Domain\View;

readonly class Envelope implements EnvelopeInterface
{
    private string $uuid;
    private string $createdAt;
    private string $updatedAt;
    private string $currentBudget;
    private string $targetBudget;
    private string $title;
    private string $userUuid;

    private function __construct(
        string $uuid,
        string $createdAt,
        string $updatedAt,
        string $currentBudget,
        string $targetBudget,
        string $title,
        string $userUuid,
    ) {
        $this->uuid = $uuid;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->currentBudget = $currentBudget;
        $this->targetBudget = $targetBudget;
        $this->title = $title;
        $this->userUuid = $userUuid;
    }

    public static function create(array $envelope): self
    {
        return new self(
            $envelope['uuid'],
            $envelope['created_at'],
            $envelope['updated_at'],
            $envelope['current_budget'],
            $envelope['target_budget'],
            $envelope['title'],
            $envelope['user_uuid'],
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
