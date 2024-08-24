<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;

class CurrentBudgetValidator
{
    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function validate(string $currentBudget, string $targetBudget, ?EnvelopeInterface $parentEnvelope, ?EnvelopeInterface $currentEnvelope = null): void
    {
        if ($currentBudget > $targetBudget) {
            throw new EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException::MESSAGE, 400);
        }

        if ($parentEnvelope instanceof EnvelopeInterface && floatval($currentBudget) > floatval($parentEnvelope->getTargetBudget())) {
            throw new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }

        if ($currentEnvelope instanceof EnvelopeInterface && $currentBudget < $this->calculateTotalChildrenCurrentBudget($currentEnvelope)) {
            throw new ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException(ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException::MESSAGE, 400);
        }
    }

    private function calculateTotalChildrenCurrentBudget(EnvelopeInterface $currentEnvelope): float
    {
        $children = $currentEnvelope->getChildren();

        return $children->reduce(
            fn (float $carry, EnvelopeInterface $child) => $carry + floatval($child->getCurrentBudget()),
            0.00
        );
    }
}
