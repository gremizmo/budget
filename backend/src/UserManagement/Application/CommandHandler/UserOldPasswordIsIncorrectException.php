<?php

namespace App\UserManagement\Application\CommandHandler;

class UserOldPasswordIsIncorrectException extends \Exception
{
    public const MESSAGE = 'User old password is incorrect';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
