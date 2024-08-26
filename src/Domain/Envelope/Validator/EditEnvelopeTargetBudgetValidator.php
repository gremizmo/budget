<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Entity\EnvelopeInterface;

class EditEnvelopeTargetBudgetValidator
{
    public function validate(string $targetBudget, EnvelopeInterface $envelopeToUpdate, ?EnvelopeInterface $parentEnvelope = null): void
    {
        $targetBudgetFloat = floatval($targetBudget);

        $envelopeToUpdate->validateAgainstCurrentEnvelope($envelopeToUpdate->calculateChildrenTargetBudget(), $targetBudgetFloat);

        if ($parentEnvelope instanceof EnvelopeInterface) {
            $parentEnvelope->validateAgainstParentAvailableTargetBudget($targetBudgetFloat, $parentEnvelope->calculateAvailableTargetBudget(), floatval($envelopeToUpdate->getTargetBudget()));
            $parentEnvelope->validateAgainstParentTargetBudget($parentEnvelope->calculateTotalChildrenCurrentBudgetOfParentEnvelope($envelopeToUpdate));
            $parentEnvelope->validateMaxAllowedTargetBudgetAvailable($envelopeToUpdate, $targetBudgetFloat);
        }
    }
}
