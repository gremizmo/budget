<?php

namespace App\Infra\Http\Rest\User\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class RequestPasswordResetControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred on request password reset in RequestPasswordResetController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
