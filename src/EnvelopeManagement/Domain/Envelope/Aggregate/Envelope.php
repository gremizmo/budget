<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Model;

use App\EnvelopeManagement\Domain\Envelope\Exception\CurrentBudgetException;
use App\EnvelopeManagement\Domain\Envelope\Exception\TargetBudgetException;

class Envelope implements EnvelopeInterface
{
    private string $uuid;
    private \DateTime $updatedAt;
    private string $currentBudget;
    private string $targetBudget;
    private string $title;
    private \DateTimeImmutable $createdAt;
    private ?EnvelopeInterface $parent = null;
    /**
     * @var \ArrayAccess<int, EnvelopeInterface>|\IteratorAggregate<int, EnvelopeInterface>|\Serializable|\Countable
     */
    private \ArrayAccess|\IteratorAggregate|\Serializable|\Countable $children;

    private string $userUuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return \ArrayAccess<int, EnvelopeInterface>|\IteratorAggregate<int, EnvelopeInterface>|\Serializable|\Countable
     */
    public function getChildren(): \ArrayAccess|\IteratorAggregate|\Serializable|\Countable
    {
        return $this->children;
    }

    public function setChildren(\Countable|\IteratorAggregate|\Serializable|\ArrayAccess $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getTargetBudget(): string
    {
        return $this->targetBudget;
    }

    public function getCurrentBudget(): string
    {
        return $this->currentBudget;
    }

    public function getParent(): ?EnvelopeInterface
    {
        return $this->parent;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setCurrentBudget(string $currentBudget): self
    {
        $this->currentBudget = $currentBudget;

        return $this;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function setUserUuid(string $userUuid): self
    {
        $this->userUuid = $userUuid;

        return $this;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        $this->getParent()?->setUpdatedAt($updatedAt);

        return $this;
    }

    public function setTargetBudget(string $targetBudget): self
    {
        $this->targetBudget = $targetBudget;

        return $this;
    }

    public function setParent(?EnvelopeInterface $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function addChild(EnvelopeInterface $child): self
    {
        $this->children->add($child);

        return $this;
    }

    public function calculateChildrenCurrentBudgetOfParentEnvelope(EnvelopeInterface $envelopeToUpdate): float
    {
        return array_reduce(
            $this->getChildren()->toArray(),
            fn (float $carry, EnvelopeInterface $child) => $child->getUuid() === $envelopeToUpdate->getUuid() ? $carry : $carry + floatval($child->getCurrentBudget()),
            0.00,
        );
    }

    /**
     * @throws TargetBudgetException
     */
    public function validateTargetBudgetIsLessThanParentTargetBudget(float $targetBudget): void
    {
        if ($targetBudget > $this->calculateMaxAllowableTargetBudget()) {
            throw TargetBudgetException::createFromTargetBudgetExceedsParentMaxAllowableBudget();
        }
    }

    /**
     * @throws TargetBudgetException
     */
    public function validateTargetBudgetIsLessThanParentAvailableTargetBudget(float $targetBudget, float $envelopeToUpdateTargetBudget): void
    {
        if ($targetBudget !== $envelopeToUpdateTargetBudget && ($targetBudget - $envelopeToUpdateTargetBudget) > $this->calculateAvailableTargetBudget()) {
            throw TargetBudgetException::createFromTargetBudgetExceedsParentAvailableTargetBudget();
        }
    }

    /**
     * @throws CurrentBudgetException
     */
    public function validateChildrenCurrentBudgetIsLessThanTargetBudget(float $childrenCurrentBudget): void
    {
        if ($childrenCurrentBudget > floatval($this->getTargetBudget())) {
            throw CurrentBudgetException::createFromChildrenCurrentBudgetExceedsTargetBudget();
        }
    }

    /**
     * @throws TargetBudgetException
     */
    public function validateParentEnvelopeChildrenTargetBudgetIsLessThanTargetBudgetInput(float $targetBudget): void
    {
        if ($this->calculateChildrenTargetBudget() + $targetBudget > floatval($this->getTargetBudget())) {
            throw TargetBudgetException::createFromChildrenTargetBudgetsExceedsParentEnvelopeTargetBudget();
        }
    }

    /**
     * @throws TargetBudgetException
     */
    public function validateEnvelopeChildrenTargetBudgetIsLessThanTargetBudget(float $targetBudget): void
    {
        if ($this->calculateChildrenTargetBudget() > $targetBudget) {
            throw TargetBudgetException::createFromChildrenTargetBudgetsExceedsEnvelopeTargetBudget();
        }
    }

    /**
     * @throws TargetBudgetException
     */
    public function validateTargetBudgetIsLessThanParentMaxAllowableBudget(EnvelopeInterface $envelopeToUpdate, float $targetBudgetInput): void
    {
        if (floatval($this->getTargetBudget()) < $targetBudgetInput + floatval($this->getCurrentBudget()) && $envelopeToUpdate->getParent()?->getUuid() !== $this->getUuid()) {
            throw TargetBudgetException::createFromTargetBudgetExceedsParentMaxAllowableBudget();
        }
    }

    /**
     * @throws CurrentBudgetException
     */
    public function validateCurrentBudgetIsLessThanTargetBudget(float $currentBudget, float $targetBudget): void
    {
        if ($currentBudget > $targetBudget) {
            throw CurrentBudgetException::createFromCurrentBudgetExceedsTargetBudget();
        }
    }

    /**
     * @throws CurrentBudgetException
     */
    public function validateCurrentBudgetIsLessThanParentTargetBudget(float $currentBudget): void
    {
        if ($currentBudget > floatval($this->getTargetBudget())) {
            throw CurrentBudgetException::createFromCurrentBudgetExceedsParentEnvelopeTargetBudget();
        }
    }

    /**
     * @throws CurrentBudgetException
     */
    public function validateChildrenCurrentBudgetIsLessThanCurrentBudget(float $currentBudget): void
    {
        if ($currentBudget < $this->calculateChildrenCurrentBudget()) {
            throw CurrentBudgetException::createFromChildrenCurrentBudgetExceedsCurrentBudget();
        }
    }

    /**
     * @throws CurrentBudgetException
     */
    public function updateAncestorsCurrentBudget(float $currentBudget): void
    {
        $this->setCurrentBudget(
            \number_format(
                num: \floatval($this->getCurrentBudget()) + $currentBudget,
                decimals: 2,
                thousands_separator: ''
            )
        );

        if ($this->getCurrentBudget() > $this->getTargetBudget()) {
            throw CurrentBudgetException::createFromCurrentBudgetExceedsEnvelopeTargetBudget();
        }

        $this->getParent()?->updateAncestorsCurrentBudget($currentBudget);
    }

    private function calculateChildrenCurrentBudget(): float
    {
        return array_reduce(
            $this->getChildren()->toArray(),
            fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getCurrentBudget()),
            0.00,
        );
    }

    private function calculateChildrenTargetBudget(): float
    {
        return array_reduce(
            $this->getChildren()->toArray(),
            fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getTargetBudget()),
            0.00,
        );
    }

    private function calculateMaxAllowableTargetBudget(): float
    {
        return floatval($this->getTargetBudget()) - (floatval($this->getCurrentBudget()) + ($this->calculateChildrenTargetBudget() - $this->calculateChildrenCurrentBudget()));
    }

    private function calculateAvailableTargetBudget(): float
    {
        return floatval($this->getTargetBudget()) - floatval($this->getCurrentBudget());
    }
}
