<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Entity\EnvelopeInterface;

class EditEnvelopeCurrentBudgetValidator
{
    public function validate(string $currentBudget, string $targetBudget, EnvelopeInterface $currentEnvelope, ?EnvelopeInterface $parentEnvelope): void
    {
        $currentBudgetFloat = floatval($currentBudget);
        $currentEnvelope->validateCurrentBudgetExceedsTargetBudget($currentBudgetFloat, floatval($targetBudget));

        if ($parentEnvelope instanceof EnvelopeInterface) {
            $currentEnvelope->validateCurrentBudgetExceedsParentTargetBudget($currentBudgetFloat, floatval($parentEnvelope->getTargetBudget()));
        }

        $currentEnvelope->validateCurrentBudgetLessThanChildrenCurrentBudget($currentBudgetFloat);
    }
}
