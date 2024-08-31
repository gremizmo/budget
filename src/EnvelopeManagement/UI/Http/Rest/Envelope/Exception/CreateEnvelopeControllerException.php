<?php

namespace App\EnvelopeManagement\UI\Http\Rest\Envelope\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CreateEnvelopeControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred while creating an envelope in CreateEnvelopeController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
