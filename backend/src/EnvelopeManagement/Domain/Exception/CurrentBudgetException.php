<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Exception;

class CurrentBudgetException extends \LogicException
{
    private function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function exceedsCreditLimit(): self
    {
        return new self(
            'Credit limit exceeded.',
            400,
        );
    }

    public static function exceedsDebitLimit(): self
    {
        return new self(
            'Debit limit exceeded.',
            400,
        );
    }
}
