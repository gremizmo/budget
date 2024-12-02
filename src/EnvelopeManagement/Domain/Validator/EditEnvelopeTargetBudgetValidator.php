<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Validator;

use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;

class EditEnvelopeTargetBudgetValidator
{
    public function validate(string $targetBudget, EnvelopeInterface $envelopeToUpdate, ?EnvelopeInterface $parentEnvelope = null): void
    {
        $targetBudgetFloat = floatval($targetBudget);
        $envelopeToUpdate->validateEnvelopeChildrenTargetBudgetIsLessThanTargetBudget($targetBudgetFloat);

        if ($parentEnvelope instanceof EnvelopeInterface) {
            $parentEnvelope->validateTargetBudgetIsLessThanParentAvailableTargetBudget($targetBudgetFloat, floatval($envelopeToUpdate->getTargetBudget()));
            $parentEnvelope->validateChildrenCurrentBudgetIsLessThanTargetBudget($parentEnvelope->calculateChildrenCurrentBudgetOfParentEnvelope($envelopeToUpdate));
            $parentEnvelope->validateTargetBudgetIsLessThanParentMaxAllowableBudget($envelopeToUpdate, $targetBudgetFloat);
        }
    }
}
