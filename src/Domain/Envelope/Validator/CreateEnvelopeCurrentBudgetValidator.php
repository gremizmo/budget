<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Model\EnvelopeInterface;

class CreateEnvelopeCurrentBudgetValidator
{
    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function validate(string $currentBudget, string $targetBudget, ?EnvelopeInterface $parentEnvelope): void
    {
        if (floatval($currentBudget) > floatval($targetBudget)) {
            throw new EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException::MESSAGE, 400);
        }

        if ($parentEnvelope instanceof EnvelopeInterface && floatval($currentBudget) > floatval($parentEnvelope->getTargetBudget())) {
            throw new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }
    }
}
