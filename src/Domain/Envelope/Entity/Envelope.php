<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Entity;

use App\Domain\Envelope\Exception\ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\User\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;

class Envelope implements EnvelopeInterface
{
    private int $id;
    private \DateTimeImmutable $createdAt;
    private \DateTime $updatedAt;
    private string $currentBudget = '0.00';
    private string $targetBudget = '0.00';
    private string $title = '';
    private ?EnvelopeInterface $parent = null;
    private Collection $children;
    private UserInterface $user;

    public function __construct()
    {
        $this->children = new EnvelopeCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getParent(): ?EnvelopeInterface
    {
        return $this->parent;
    }

    public function setParent(?EnvelopeInterface $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    public function getCurrentBudget(): string
    {
        return $this->currentBudget;
    }

    public function setCurrentBudget(string $currentBudget): self
    {
        $this->currentBudget = $currentBudget;

        return $this;
    }

    public function getTargetBudget(): string
    {
        return $this->targetBudget;
    }

    public function setTargetBudget(string $targetBudget): self
    {
        $this->targetBudget = $targetBudget;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setChildren(EnvelopeCollectionInterface $envelopes): self
    {
        $this->children = $envelopes;

        return $this;
    }

    public function addChild(EnvelopeInterface $child): self
    {
        if (!$this->getChildren()->contains($child)) {
            $this->getChildren()->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function calculateChildrenTargetBudget(): float
    {
        return array_reduce(
            $this->getChildren()->toArray(),
            fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getTargetBudget()),
            0.00,
        );
    }

    public function calculateTotalChildrenCurrentBudgetOfParentEnvelope(EnvelopeInterface $envelopeToUpdate): float
    {
        return array_reduce(
            $this->getChildren()->toArray(),
            fn (float $carry, EnvelopeInterface $child) => $child->getId() === $envelopeToUpdate->getId() ? $carry : $carry + floatval($child->getCurrentBudget()),
            0.00,
        );
    }

    public function calculateTotalChildrenCurrentBudget(): float
    {
        return array_reduce(
            $this->getChildren()->toArray(),
            fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getCurrentBudget()),
            0.00,
        );
    }

    public function calculateTotalChildrenTargetBudgetOfParentEnvelope(?EnvelopeInterface $envelopeToUpdate = null): float
    {
        return array_reduce(
            $this->getChildren()->toArray(),
            fn (float $carry, EnvelopeInterface $child) => $child->getId() === $envelopeToUpdate?->getId() ? $carry : $carry + floatval($child->getTargetBudget()),
            0.00,
        );
    }

    public function calculateTotalChildrenTargetBudget(): float
    {
        return array_reduce(
            $this->getChildren()->toArray(),
            fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getTargetBudget()),
            0.00,
        );
    }

    public function calculateAvailableTargetBudget(): float
    {
        return floatval($this->getTargetBudget()) - floatval($this->getCurrentBudget());
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function validateAgainstParentAvailableTargetBudget(float $targetBudgetFloat, float $availableTargetBudget, float $envelopeToUpdateTargetBudget): void
    {
        if ($targetBudgetFloat !== $envelopeToUpdateTargetBudget && ($targetBudgetFloat - $envelopeToUpdateTargetBudget) > $availableTargetBudget) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function validateAgainstParentTargetBudget(float $totalChildrenTargetBudget): void
    {
        if ($totalChildrenTargetBudget > floatval($this->getTargetBudget())) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function validateAgainstCurrentEnvelope(float $totalChildrenTargetBudget, float $targetBudgetFloat): void
    {
        if ($totalChildrenTargetBudget > $targetBudgetFloat) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function validateMaxAllowedTargetBudgetAvailable(EnvelopeInterface $envelopeToUpdate, float $targetBudgetFloat): void
    {
        if ($targetBudgetFloat > $this->calculateMaxAllowableTargetBudget($envelopeToUpdate)) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function validateCurrentBudgetExceedsTargetBudget(float $currentBudget, float $targetBudget): void
    {
        if ($currentBudget > $targetBudget) {
            throw new EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    public function validateCurrentBudgetExceedsParentTargetBudget(float $currentBudget, float $parentTargetBudget): void
    {
        if ($currentBudget > $parentTargetBudget) {
            throw new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     */
    public function validateCurrentBudgetLessThanChildrenCurrentBudget(float $currentBudget): void
    {
        if ($currentBudget < $this->calculateTotalChildrenCurrentBudget()) {
            throw new ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException(ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException::MESSAGE, 400);
        }
    }

    public function isTargetBudgetInputLessThanCurrentTargetBudget(float $targetBudget): bool
    {
        return $targetBudget <= floatval($this->getTargetBudget());
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
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
            throw new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }

        $this->getParent()?->updateAncestorsCurrentBudget($currentBudget);
    }

    private function calculateMaxAllowableTargetBudget(EnvelopeInterface $envelopeToUpdate): float
    {
        return floatval($this->getTargetBudget()) - (floatval($this->getCurrentBudget()) + $this->calculateTotalChildrenBudgetDiff($envelopeToUpdate)) + floatval($envelopeToUpdate->getCurrentBudget());
    }

    private function calculateTotalChildrenBudgetDiff(EnvelopeInterface $envelopeToUpdate): float
    {
        return $this->calculateTotalChildrenTargetBudgetOfParentEnvelope($envelopeToUpdate) - $this->calculateTotalChildrenCurrentBudgetOfParentEnvelope($envelopeToUpdate);
    }
}
