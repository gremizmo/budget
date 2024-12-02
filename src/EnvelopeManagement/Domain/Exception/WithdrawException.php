<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Exception;

class WithdrawException extends \LogicException
{
    private function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function exceedsWithdrawalLimit(): self
    {
        return new self(
            'Withdrawal limit exceeded.',
            400,
        );
    }
}
