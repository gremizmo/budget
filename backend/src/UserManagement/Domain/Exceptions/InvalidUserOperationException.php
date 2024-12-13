<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Exceptions;

final class InvalidUserOperationException extends \LogicException
{
    private function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function operationOnDeletedUser(): self
    {
        return new self(
            'Cannot modify a deleted user.',
            400,
        );
    }

    public static function operationOnResetUserPassword(): self
    {
        return new self(
            'User password reset token is expired.',
            401,
        );
    }
}
