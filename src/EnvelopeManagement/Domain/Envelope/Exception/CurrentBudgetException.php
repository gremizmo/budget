<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Exception;

class CurrentBudgetException extends \Exception
{
    private function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function createFromChildrenCurrentBudgetExceedsCurrentBudget(): self
    {
        return new self(
            'Total current budget of children envelopes exceeds the envelope\'s current budget',
            400,
        );
    }

    public static function createFromChildrenCurrentBudgetExceedsTargetBudget(): self
    {
        return new self(
            'Total children current budget exceeds the envelope\'s target budget',
            400,
        );
    }

    public static function createFromCurrentBudgetExceedsEnvelopeTargetBudget(): self
    {
        return new self(
            'Current budget of envelope exceeds the envelope\'s target budget',
            400,
        );
    }

    public static function createFromCurrentBudgetExceedsParentEnvelopeTargetBudget(): self
    {
        return new self(
            'Current budget of parent envelope exceeds the parent envelope\'s target budget',
            400,
        );
    }

    public static function createFromCurrentBudgetExceedsTargetBudget(): self
    {
        return new self(
            'Current budget of exceeds target budget',
            400,
        );
    }
}
