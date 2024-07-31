<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

class UserCommandRepositoryException extends \Exception
{
    public const MESSAGE = 'An Error occurred in UserCommandRepository';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
