<?php

namespace App\Domain\User\Exception;

class UserPasswordResetTokenIsExpiredException extends \Exception
{
    public const MESSAGE = 'User password reset token is expired';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
