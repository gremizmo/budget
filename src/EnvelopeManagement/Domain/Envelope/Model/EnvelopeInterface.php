<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Model;

interface EnvelopeInterface
{
    public function getId(): int;

    public function setTitle(string $title): self;

    /**
     * @return \ArrayAccess<int, EnvelopeInterface>
     */
    public function getChildren(): \ArrayAccess|\IteratorAggregate|\Serializable|\Countable;

    public function getTargetBudget(): string;

    public function getCurrentBudget(): string;

    public function getParent(): ?EnvelopeInterface;

    public function setCurrentBudget(string $currentBudget): self;

    public function getUserId(): int;

    public function setUpdatedAt(\DateTime $updatedAt): self;

    public function setParent(?EnvelopeInterface $parent = null): self;

    public function setTargetBudget(string $targetBudget): self;

    public function calculateChildrenCurrentBudgetOfParentEnvelope(EnvelopeInterface $envelopeToUpdate): float;

    public function validateTargetBudgetIsLessThanParentTargetBudget(float $targetBudget): void;

    public function validateTargetBudgetIsLessThanParentAvailableTargetBudget(float $targetBudget, float $envelopeToUpdateTargetBudget): void;

    public function validateChildrenCurrentBudgetIsLessThanTargetBudget(float $childrenCurrentBudget): void;

    public function validateParentEnvelopeChildrenTargetBudgetIsLessThanTargetBudgetInput(float $targetBudget): void;

    public function validateEnvelopeChildrenTargetBudgetIsLessThanTargetBudget(float $targetBudget): void;

    public function validateTargetBudgetIsLessThanParentMaxAllowableBudget(EnvelopeInterface $envelopeToUpdate, float $targetBudgetInput): void;

    public function validateCurrentBudgetIsLessThanTargetBudget(float $currentBudget, float $targetBudget): void;

    public function validateCurrentBudgetIsLessThanParentTargetBudget(float $currentBudget): void;

    public function validateChildrenCurrentBudgetIsLessThanCurrentBudget(float $currentBudget): void;

    public function updateAncestorsCurrentBudget(float $currentBudget): void;
}
