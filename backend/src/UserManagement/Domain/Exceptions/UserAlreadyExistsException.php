<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Exceptions;

final class UserAlreadyExistsException extends \LogicException
{
    public const string MESSAGE = 'User already exists';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
