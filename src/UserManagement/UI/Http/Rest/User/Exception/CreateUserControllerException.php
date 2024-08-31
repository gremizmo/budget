<?php

namespace App\UserManagement\UI\Http\Rest\User\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CreateUserControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred while creating a user in CreateUserController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
