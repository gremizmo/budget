<?php

namespace App\UserManagement\Domain\Exceptions;

final class UserOldPasswordIsIncorrectException extends \Exception
{
    public const string MESSAGE = 'User old password is incorrect';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
