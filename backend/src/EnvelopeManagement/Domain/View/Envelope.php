<?php

namespace App\EnvelopeManagement\Domain\View;

final class Envelope implements EnvelopeInterface
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

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setCurrentBudget(string $currentBudget): self
    {
        $this->currentBudget = $currentBudget;

        return $this;
    }

    public function setTargetBudget(string $targetBudget): self
    {
        $this->targetBudget = $targetBudget;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setUserUuid(string $userUuid): self
    {
        $this->userUuid = $userUuid;

        return $this;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
