<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Exception;

class TargetBudgetException extends \LogicException
{
    private function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function createFromChildrenTargetBudgetsExceedsEnvelopeTargetBudget(): self
    {
        return new self(
            'Total target budget of children envelopes exceeds envelope\'s target budget',
            400,
        );
    }

    public static function createFromChildrenTargetBudgetsExceedsParentEnvelopeTargetBudget(): self
    {
        return new self(
            'Total target budget of children envelopes exceeds the parent envelope\'s target budget',
            400,
        );
    }

    public static function createFromTargetBudgetExceedsParentAvailableTargetBudget(): self
    {
        return new self(
            'Target budget exceeds parent available target budget',
            400,
        );
    }

    public static function createFromTargetBudgetExceedsParentMaxAllowableBudget(): self
    {
        return new self(
            'Target budget exceeds parent max allowable budget',
            400,
        );
    }
}
