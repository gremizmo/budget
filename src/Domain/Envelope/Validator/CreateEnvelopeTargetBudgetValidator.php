<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Model\EnvelopeInterface;

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
