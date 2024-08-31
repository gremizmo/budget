<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\CommandHandler;

class ChangeUserPasswordCommandHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while changing the user password in ChangeUserPasswordCommandHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
