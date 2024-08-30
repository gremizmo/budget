<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

class EnvelopeNotFoundException extends \Exception
{
    public const MESSAGE = 'Envelope not found';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
