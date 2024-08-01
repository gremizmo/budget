<?php

namespace App\Domain\Envelope\Service;

use App\Domain\Envelope\Entity\EnvelopeInterface;

interface TargetBudgetValidatorInterface
{
    public function validate(float $targetBudget, ?EnvelopeInterface $parentEnvelope = null, ?EnvelopeInterface $currentEnvelope = null): void;
}
