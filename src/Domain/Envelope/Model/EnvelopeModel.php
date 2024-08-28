<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Model;

use App\Domain\Envelope\Exception\ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Shared\Model\Collection;
use App\Domain\Shared\Model\UserInterface;

class EnvelopeModel implements EnvelopeInterface
{
    protected int $id;
    protected \DateTimeImmutable $createdAt;
    protected \DateTime $updatedAt;
    protected string $currentBudget;
    protected string $targetBudget;
    protected string $title;
    protected ?EnvelopeInterface $parent = null;
    protected \ArrayAccess|\IteratorAggregate|\Serializable|\Countable $children;
    protected UserInterface $user;

    public function __construct()
    {
        $this->children = new Collection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getChildren(): \ArrayAccess|\IteratorAggregate|\Serializable|\Countable
    {
        return $this->children;
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

    public function setCurrentBudget(string $currentBudget): self
    {
        $this->currentBudget = $currentBudget;

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
    public function validateAgainstParentAvailableTargetBudget(float $targetBudget, float $availableTargetBudget, float $envelopeToUpdateTargetBudget): void
    {
        if ($targetBudget !== $envelopeToUpdateTargetBudget && ($targetBudget - $envelopeToUpdateTargetBudget) > $availableTargetBudget) {
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
    public function validateAgainstCurrentEnvelope(float $totalChildrenTargetBudget, float $targetBudget): void
    {
        if ($totalChildrenTargetBudget > $targetBudget) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function validateMaxAllowedTargetBudgetAvailable(EnvelopeInterface $envelope, float $targetBudget): void
    {
        if (($this->getTargetBudget() < $targetBudget + floatval($this->getCurrentBudget())) && floatval($envelope->getTargetBudget()) !== $targetBudget) {
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
}
