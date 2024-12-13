<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Exceptions;

final class TargetBudgetException extends \LogicException
{
    private function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function isBelowZero(): self
    {
        return new self(
            'Target budget must be greater than 0.',
            400,
        );
    }
}
