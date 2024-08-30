<?php

declare(strict_types=1);

namespace App\Application\User\QueryHandler;

class GetUserByPasswordResetTokenQueryHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while getting user by password reset token in GetUserByPasswordResetTokenQueryHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
