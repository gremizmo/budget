<?php

declare(strict_types=1);

namespace App\Application\User\QueryHandler;

class ShowUserQueryHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while getting a user in ShowUserQueryHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
