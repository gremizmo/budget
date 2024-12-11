<?php

declare(strict_types=1);

namespace App\UserManagement\Application\QueryHandler;

class UserNotFoundException extends \Exception
{
    public const MESSAGE = 'User not found';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
