<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Model\EnvelopeInterface;

class EditEnvelopeCurrentBudgetValidator
{
    public function validate(string $currentBudget, string $targetBudget, EnvelopeInterface $currentEnvelope, ?EnvelopeInterface $parentEnvelope): void
    {
        $currentBudgetFloat = floatval($currentBudget);
        $currentEnvelope->validateCurrentBudgetIsLessThanTargetBudget($currentBudgetFloat, floatval($targetBudget));

        if ($parentEnvelope instanceof EnvelopeInterface) {
            $parentEnvelope->validateCurrentBudgetIsLessThanParentTargetBudget($currentBudgetFloat);
        }

        $currentEnvelope->validateChildrenCurrentBudgetIsLessThanCurrentBudget($currentBudgetFloat);
    }
}
