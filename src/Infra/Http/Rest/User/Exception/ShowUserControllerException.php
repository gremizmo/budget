<?php

namespace App\Infra\Http\Rest\User\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ShowUserControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred while getting a user in ShowUserController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
