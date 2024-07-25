<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class EnvelopeNotFoundException extends \Exception
{
    public function __construct(
        string $message = 'Envelope not found.',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
