<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Exception\CurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\CurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Model\EnvelopeInterface;

class CreateEnvelopeCurrentBudgetValidator
{
    /**
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws CurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function validate(string $currentBudget, string $targetBudget, ?EnvelopeInterface $parentEnvelope): void
    {
        if (floatval($currentBudget) > floatval($targetBudget)) {
            throw new CurrentBudgetExceedsEnvelopeTargetBudgetException(CurrentBudgetExceedsEnvelopeTargetBudgetException::MESSAGE, 400);
        }

        if ($parentEnvelope instanceof EnvelopeInterface && floatval($currentBudget) > floatval($parentEnvelope->getTargetBudget())) {
            throw new CurrentBudgetExceedsParentEnvelopeTargetBudgetException(CurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }
}
