<?php

namespace App\Domain\User\Exception;

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
