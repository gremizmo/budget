<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;

class EditEnvelopeBuilder
{
    private ?EnvelopeInterface $envelope = null;
    private ?UpdateEnvelopeDtoInterface $updateEnvelopeDto = null;
    private ?EnvelopeInterface $parentEnvelope = null;

    public function setEnvelope(EnvelopeInterface $envelope): self
    {
        $this->envelope = $envelope;

        return $this;
    }

    public function setUpdateEnvelopeDto(UpdateEnvelopeDtoInterface $updateEnvelopeDto): self
    {
        $this->updateEnvelopeDto = $updateEnvelopeDto;

        return $this;
    }

    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self
    {
        $this->parentEnvelope = $parentEnvelope;

        return $this;
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    public function build(): EnvelopeInterface
    {
        if (!$this->envelope || !$this->updateEnvelopeDto) {
            throw new \InvalidArgumentException('Envelope and UpdateEnvelopeDto must be set.');
        }

        $this->validateTargetBudget($this->updateEnvelopeDto->getTargetBudget());
        $this->validateCurrentBudget($this->updateEnvelopeDto->getCurrentBudget());

        $difference = floatval($this->updateEnvelopeDto->getCurrentBudget()) - floatval($this->envelope->getCurrentBudget());

        $this->envelope->setParent($this->parentEnvelope)
            ->setTitle($this->updateEnvelopeDto->getTitle())
            ->setCurrentBudget($this->updateEnvelopeDto->getCurrentBudget())
            ->setTargetBudget($this->updateEnvelopeDto->getTargetBudget())
            ->setUpdatedAt(new \DateTime('now'));

        if (0.00 !== $difference && $this->parentEnvelope) {
            $this->updateParentCurrentBudget($difference);
        }

        return $this->envelope;
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
        } else {
            $totalChildrenTargetBudget = $this->calculateTotalChildrenTargetBudget();
            if ($totalChildrenTargetBudget > $targetBudgetFloat) {
                throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
            }
        }
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

    private function calculateTotalChildrenTargetBudget(): float
    {
        if ($this->parentEnvelope instanceof EnvelopeInterface) {
            return $this->parentEnvelope->getChildren()->reduce(
                fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getTargetBudget()),
                0.00
            );
        } else {
            return $this->envelope->getChildren()->reduce(
                fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getTargetBudget()),
                0.00
            );
        }
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateParentCurrentBudget(float $difference): void
    {
        $this->parentEnvelope->setCurrentBudget(
            \number_format(
                num: \floatval($this->parentEnvelope->getCurrentBudget()) + $difference,
                decimals: 2,
                thousands_separator: ''
            )
        );

        if ($this->parentEnvelope->getCurrentBudget() > $this->parentEnvelope->getTargetBudget()) {
            throw new ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }
}
