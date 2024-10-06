<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Validator;

use App\EnvelopeManagement\Domain\Envelope\Exception\CurrentBudgetException;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

class CreateEnvelopeCurrentBudgetValidator
{
    /**
     * @throws CurrentBudgetException
     */
    public function validate(string $currentBudget, string $targetBudget, ?EnvelopeInterface $parentEnvelope): void
    {
        if (floatval($currentBudget) > floatval($targetBudget)) {
            throw CurrentBudgetException::createFromCurrentBudgetExceedsEnvelopeTargetBudget();
        }

        if ($parentEnvelope instanceof EnvelopeInterface && floatval($currentBudget) > floatval($parentEnvelope->getTargetBudget())) {
            throw CurrentBudgetException::createFromCurrentBudgetExceedsParentEnvelopeTargetBudget();
        }
    }
}
