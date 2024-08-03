<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\User\Entity\UserInterface;

class CreateEnvelopeBuilder
{
    private ?EnvelopeInterface $parentEnvelope = null;
    private ?CreateEnvelopeDtoInterface $createEnvelopeDto = null;
    private ?UserInterface $user = null;

    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self
    {
        $this->parentEnvelope = $parentEnvelope;

        return $this;
    }

    public function setCreateEnvelopeDto(CreateEnvelopeDtoInterface $createEnvelopeDto): self
    {
        $this->createEnvelopeDto = $createEnvelopeDto;

        return $this;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function build(): EnvelopeInterface
    {
        if (!$this->createEnvelopeDto || !$this->user) {
            throw new \InvalidArgumentException('CreateEnvelopeDto and User must be set.');
        }

        $envelope = new Envelope();

        $this->validateTargetBudget($this->createEnvelopeDto->getTargetBudget());
        $this->validateCurrentBudget($this->createEnvelopeDto->getCurrentBudget());

        $currentBudget = floatval($this->createEnvelopeDto->getCurrentBudget());

        $envelope->setParent($this->parentEnvelope)
            ->setCurrentBudget($this->createEnvelopeDto->getCurrentBudget())
            ->setTargetBudget($this->createEnvelopeDto->getTargetBudget())
            ->setTitle($this->createEnvelopeDto->getTitle())
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setUpdatedAt(new \DateTime('now'))
            ->setUser($this->user);

        if (0.00 !== $currentBudget && $this->parentEnvelope) {
            $this->updateParentCurrentBudget($currentBudget);
        }

        return $envelope;
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    private function validateTargetBudget(string $targetBudget): void
    {
        $targetBudgetFloat = floatval($targetBudget);

        if ($this->parentEnvelope instanceof EnvelopeInterface) {
            $totalChildrenTargetBudget = $this->calculateTotalChildrenTargetBudget();
            if ($totalChildrenTargetBudget + $targetBudgetFloat > floatval($this->parentEnvelope->getTargetBudget())) {
                throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
            }
        }
    }

    private function calculateTotalChildrenTargetBudget(): float
    {
        return $this->parentEnvelope->getChildren()->reduce(
            fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getTargetBudget()),
            0.00
        );
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function validateCurrentBudget(string $currentBudget): void
    {
        if ($this->parentEnvelope && floatval($currentBudget) > floatval($this->parentEnvelope->getTargetBudget())) {
            throw new ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateParentCurrentBudget(float $currentBudget): void
    {
        $this->parentEnvelope->setCurrentBudget(
            \number_format(
                num: \floatval($this->parentEnvelope->getCurrentBudget()) + $currentBudget,
                decimals: 2,
                thousands_separator: ''
            )
        );

        if ($this->parentEnvelope->getCurrentBudget() > $this->parentEnvelope->getTargetBudget()) {
            throw new ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }
}
