<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Entity;

use App\Domain\User\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;

interface EnvelopeInterface
{
    public function getId(): int;

    public function getParent(): ?EnvelopeInterface;

    public function setParent(?EnvelopeInterface $parent = null): self;

    public function getCreatedAt(): \DateTimeImmutable;

    public function setCreatedAt(\DateTimeImmutable $createdAt): self;

    public function getUpdatedAt(): \DateTime;

    public function setUpdatedAt(\DateTime $updatedAt): self;

    public function getCurrentBudget(): string;

    public function setCurrentBudget(string $currentBudget): self;

    public function getTargetBudget(): string;

    public function setTargetBudget(string $targetBudget): self;

    public function getTitle(): string;

    public function setTitle(string $title): self;

    public function setChildren(EnvelopeCollectionInterface $envelopes): self;

    public function addChild(EnvelopeInterface $child): self;

    public function getChildren(): Collection;

    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): self;

    public function calculateTotalChildrenCurrentBudgetOfParentEnvelope(EnvelopeInterface $envelopeToUpdate): float;

    public function calculateTotalChildrenCurrentBudget(): float;

    public function calculateTotalChildrenTargetBudgetOfParentEnvelope(?EnvelopeInterface $envelopeToUpdate = null): float;

    public function calculateTotalChildrenTargetBudget(): float;

    public function calculateAvailableTargetBudget(): float;

    public function isTargetBudgetInputLessThanCurrentTargetBudget(float $targetBudget): bool;

    public function validateAgainstParentAvailableTargetBudget(float $targetBudgetFloat, float $availableTargetBudget, float $envelopeToUpdateTargetBudget): void;

    public function validateAgainstParentTargetBudget(float $totalChildrenTargetBudget): void;

    public function validateAgainstCurrentEnvelope(float $totalChildrenTargetBudget, float $targetBudgetFloat): void;

    public function validateMaxAllowedTargetBudgetAvailable(EnvelopeInterface $envelopeToUpdate, float $targetBudgetFloat): void;

    public function validateCurrentBudgetExceedsTargetBudget(float $currentBudget, float $targetBudget): void;

    public function validateCurrentBudgetExceedsParentTargetBudget(float $currentBudget, float $parentTargetBudget): void;

    public function validateCurrentBudgetLessThanChildrenCurrentBudget(float $currentBudget): void;

    public function calculateChildrenTargetBudget(): float;

    public function updateAncestorsCurrentBudget(float $currentBudget): void;
}
