<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

class ResetPasswordCommandHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while resetting a password in ResetPasswordCommandHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
