<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

class CreateUserCommandHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while creating a user';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
