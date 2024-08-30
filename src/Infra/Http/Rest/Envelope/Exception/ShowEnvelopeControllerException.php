<?php

namespace App\Infra\Http\Rest\Envelope\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ShowEnvelopeControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred while getting an envelope in ShowEnvelopeController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
