<?php

namespace App\Infra\Http\Rest\User\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EditUserControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred while editing a user in EditUserController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
