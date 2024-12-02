<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Validator;

use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;

class EditEnvelopeCurrentBudgetValidator
{
    public function validate(string $currentBudget, string $targetBudget, EnvelopeInterface $currentEnvelope, ?EnvelopeInterface $parentEnvelope): void
    {
        $currentBudgetFloat = floatval($currentBudget);
        $currentEnvelope->validateCurrentBudgetIsLessThanTargetBudget($currentBudgetFloat, floatval($targetBudget));
    }
}
