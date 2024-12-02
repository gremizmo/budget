<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Validator;

use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Exception\CurrentBudgetException;

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
    }
}
