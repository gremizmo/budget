<?php

namespace App\UserManagement\UI\Http\Rest\User\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ResetUserPasswordControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred on reset user password in ResetUserPasswordController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
