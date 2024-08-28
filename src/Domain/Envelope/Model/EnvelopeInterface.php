<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Model;

interface EnvelopeInterface
{
    public function getChildren(): \ArrayAccess|\IteratorAggregate|\Serializable|\Countable;

    public function getTargetBudget(): string;

    public function getCurrentBudget(): string;

    public function getParent(): ?EnvelopeInterface;

    public function setCurrentBudget(string $currentBudget): self;

    public function calculateTotalChildrenCurrentBudgetOfParentEnvelope(EnvelopeInterface $envelopeToUpdate): float;

    public function calculateTotalChildrenCurrentBudget(): float;

    public function calculateTotalChildrenTargetBudget(): float;

    public function calculateAvailableTargetBudget(): float;

    public function isTargetBudgetInputLessThanCurrentTargetBudget(float $targetBudget): bool;

    public function validateAgainstParentAvailableTargetBudget(float $targetBudget, float $availableTargetBudget, float $envelopeToUpdateTargetBudget): void;

    public function validateAgainstParentTargetBudget(float $totalChildrenTargetBudget): void;

    public function validateAgainstCurrentEnvelope(float $totalChildrenTargetBudget, float $targetBudget): void;

    public function validateMaxAllowedTargetBudgetAvailable(EnvelopeInterface $envelope, float $targetBudget): void;

    public function validateCurrentBudgetExceedsTargetBudget(float $currentBudget, float $targetBudget): void;

    public function validateCurrentBudgetExceedsParentTargetBudget(float $currentBudget, float $parentTargetBudget): void;

    public function validateCurrentBudgetLessThanChildrenCurrentBudget(float $currentBudget): void;

    public function calculateChildrenTargetBudget(): float;

    public function updateAncestorsCurrentBudget(float $currentBudget): void;
}
