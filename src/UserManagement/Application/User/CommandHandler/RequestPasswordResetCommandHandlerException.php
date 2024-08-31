<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\CommandHandler;

class RequestPasswordResetCommandHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while requesting a password reset in RequestPasswordResetCommandHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
