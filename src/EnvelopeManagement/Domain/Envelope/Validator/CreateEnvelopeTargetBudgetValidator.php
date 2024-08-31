<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Validator;

use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

class CreateEnvelopeTargetBudgetValidator
{
    public function validate(string $targetBudget, ?EnvelopeInterface $parentEnvelope): void
    {
        if ($parentEnvelope instanceof EnvelopeInterface) {
            $parentEnvelope->validateParentEnvelopeChildrenTargetBudgetIsLessThanTargetBudgetInput();
            $parentEnvelope->validateTargetBudgetIsLessThanParentTargetBudget(floatval($targetBudget));
        }
    }
}
