<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Exception;

class DepositException extends \LogicException
{
    private function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function exceedsDepositLimit(): self
    {
        return new self(
            'Deposit limit exceeded.',
            400,
        );
    }
}
