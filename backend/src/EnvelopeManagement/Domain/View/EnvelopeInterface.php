<?php

namespace App\EnvelopeManagement\Domain\View;

interface EnvelopeInterface
{
    public static function create(array $envelope): self;

    public function getUuid(): string;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;

    public function getCurrentBudget(): string;

    public function getTargetBudget(): string;

    public function getName(): string;

    public function getUserUuid(): string;

    public function isDeleted(): bool;

    public function setUuid(string $uuid): self;

    public function setCreatedAt(string $createdAt): self;

    public function setUpdatedAt(string $updatedAt): self;

    public function setCurrentBudget(string $currentBudget): self;

    public function setTargetBudget(string $targetBudget): self;

    public function setName(string $name): self;

    public function setUserUuid(string $userUuid): self;

    public function setIsDeleted(bool $isDeleted): self;
}
