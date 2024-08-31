<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\QueryHandler;

class GetUserAlreadyExistsQueryHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while checking if a user already exists in GetUserAlreadyExistsQueryHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
