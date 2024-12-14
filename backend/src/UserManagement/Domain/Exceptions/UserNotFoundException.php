<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Exceptions;

final class UserNotFoundException extends \Exception
{
    public const string MESSAGE = 'User not found';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
