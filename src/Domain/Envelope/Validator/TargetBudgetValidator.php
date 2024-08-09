<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;

class TargetBudgetValidator
{
    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function validate(string $targetBudget, ?EnvelopeInterface $parentEnvelope, ?EnvelopeInterface $currentEnvelope = null): void
    {
        $targetBudgetFloat = floatval($targetBudget);
        $totalChildrenTargetBudget = $this->calculateTotalChildrenTargetBudget($parentEnvelope, $currentEnvelope);

        if ($parentEnvelope instanceof EnvelopeInterface) {
            $this->validateParentEnvelope($targetBudgetFloat, $totalChildrenTargetBudget, $parentEnvelope, $currentEnvelope);
        } elseif ($currentEnvelope instanceof EnvelopeInterface) {
            $this->validateAgainstCurrentEnvelope($totalChildrenTargetBudget, $targetBudgetFloat);
        }
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    private function validateParentEnvelope(float $targetBudgetFloat, float $totalChildrenTargetBudget, EnvelopeInterface $parentEnvelope, ?EnvelopeInterface $currentEnvelope): void
    {
        $availableTargetBudget = $this->calculateAvailableTargetBudget($parentEnvelope);
        $this->validateAgainstAvailableBudget($targetBudgetFloat, $availableTargetBudget, floatval($currentEnvelope?->getTargetBudget()));
        $this->validateAgainstParentTargetBudget($totalChildrenTargetBudget, $parentEnvelope);

        if ($currentEnvelope && $targetBudgetFloat < floatval($currentEnvelope->getTargetBudget())) {
            return;
        }

        $parentTargetBudget = floatval($parentEnvelope->getTargetBudget());
        $parentCurrentBudget = floatval($parentEnvelope->getCurrentBudget());
        $totalChildrenCurrentBudget = $this->calculateTotalChildrenCurrentBudget($parentEnvelope, $currentEnvelope);
        $totalChildrenBudgetDiff = $totalChildrenTargetBudget - $totalChildrenCurrentBudget;
        $maxAllowableTargetBudget = $parentTargetBudget - ($parentCurrentBudget + $totalChildrenBudgetDiff) + floatval($currentEnvelope?->getCurrentBudget());

        if ($targetBudgetFloat > $maxAllowableTargetBudget) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException('Total target budget of children envelopes exceeds the parent envelope\'s available budget', 400);
        }
    }

    private function calculateAvailableTargetBudget(EnvelopeInterface $parentEnvelope): float
    {
        return floatval($parentEnvelope->getTargetBudget()) - floatval($parentEnvelope->getCurrentBudget());
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    private function validateAgainstAvailableBudget(float $targetBudgetFloat, float $availableTargetBudget, float $currentEnvelopeTargetBudget): void
    {
        if ($targetBudgetFloat !== $currentEnvelopeTargetBudget && ($targetBudgetFloat - $currentEnvelopeTargetBudget) > $availableTargetBudget) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    private function validateAgainstParentTargetBudget(float $totalChildrenTargetBudget, EnvelopeInterface $parentEnvelope): void
    {
        if ($totalChildrenTargetBudget > floatval($parentEnvelope->getTargetBudget())) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    private function validateAgainstCurrentEnvelope(float $totalChildrenTargetBudget, float $targetBudgetFloat): void
    {
        if ($totalChildrenTargetBudget > $targetBudgetFloat) {
            throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }

    private function calculateTotalChildrenTargetBudget(?EnvelopeInterface $parentEnvelope, ?EnvelopeInterface $currentEnvelope): float
    {
        if ($parentEnvelope instanceof EnvelopeInterface) {
            return $parentEnvelope->getChildren()->reduce(
                fn (float $carry, EnvelopeInterface $child) => $child->getId() === $currentEnvelope?->getId() ? $carry : $carry + floatval($child->getTargetBudget()),
                0.00
            );
        } elseif ($currentEnvelope instanceof EnvelopeInterface) {
            return $currentEnvelope->getChildren()->reduce(
                fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getTargetBudget()),
                0.00
            );
        }

        return 0.00;
    }

    private function calculateTotalChildrenCurrentBudget(EnvelopeInterface $parentEnvelope, ?EnvelopeInterface $currentEnvelope = null): float
    {
        return $parentEnvelope->getChildren()->reduce(
            fn (float $carry, EnvelopeInterface $child) => $child->getId() === $currentEnvelope?->getId() ? $carry : $carry + floatval($child->getCurrentBudget()),
            0.00
        );
    }
}
