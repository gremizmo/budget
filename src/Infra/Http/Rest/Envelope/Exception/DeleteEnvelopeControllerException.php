<?php

namespace App\Infra\Http\Rest\Envelope\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DeleteEnvelopeControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred while deleting an envelope in DeleteEnvelopeController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
