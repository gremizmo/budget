<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Model\EnvelopeInterface;

class CreateEnvelopeTargetBudgetValidator
{
    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function validate(string $targetBudget, ?EnvelopeInterface $parentEnvelope): void
    {
        $targetBudgetFloat = floatval($targetBudget);

        if ($parentEnvelope instanceof EnvelopeInterface) {
            $totalChildrenTargetBudget = $parentEnvelope->calculateChildrenTargetBudget();

            if ($totalChildrenTargetBudget > floatval($parentEnvelope->getTargetBudget())) {
                throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
            }

            $maxAllowableTargetBudget = floatval($parentEnvelope->getTargetBudget()) - (floatval($parentEnvelope->getCurrentBudget()) + ($totalChildrenTargetBudget - $parentEnvelope->calculateTotalChildrenCurrentBudget()));

            if ($targetBudgetFloat > $maxAllowableTargetBudget) {
                throw new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException('Total target budget of children envelopes exceeds the parent envelope\'s available budget', 400);
            }
        }
    }
}
