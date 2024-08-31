<?php

namespace App\EnvelopeManagement\UI\Http\Rest\Envelope\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EditEnvelopeControllerException extends BadRequestException
{
    public const MESSAGE = 'An error occurred while editing an envelope in EditEnvelopeController';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
