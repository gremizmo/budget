<?php

namespace App\EnvelopeManagement\ReadModels\Views;

interface EnvelopeViewInterface
{
    public static function createFromRepository(array $envelope): self;

    public function getUuid(): string;

    public function getCreatedAt(): \DateTimeImmutable;

    public function getUpdatedAt(): \DateTime;

    public function getCurrentBudget(): string;

    public function getTargetBudget(): string;

    public function getName(): string;

    public function getUserUuid(): string;

    public function isDeleted(): bool;

    public function setUuid(string $uuid): self;

    public function setCreatedAt(\DateTimeImmutable $createdAt): self;

    public function setUpdatedAt(\DateTime $updatedAt): self;

    public function setCurrentBudget(string $currentBudget): self;

    public function setTargetBudget(string $targetBudget): self;

    public function setName(string $name): self;

    public function setUserUuid(string $userUuid): self;

    public function setIsDeleted(bool $isDeleted): self;
}
