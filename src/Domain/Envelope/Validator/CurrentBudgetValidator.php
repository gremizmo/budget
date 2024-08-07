<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;

class CurrentBudgetValidator
{
    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    public function validate(string $currentBudget, ?EnvelopeInterface $parentEnvelope): void
    {
        if ($parentEnvelope && floatval($currentBudget) > floatval($parentEnvelope->getTargetBudget())) {
            throw new ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }
}
