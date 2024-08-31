<?php

namespace App\EnvelopeManagement\Infrastructure\Shared\Adapter;

class CommandBusAdapterException extends \Exception
{
    public const MESSAGE = 'An error occurred for envelope in CommandBusAdapter';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
