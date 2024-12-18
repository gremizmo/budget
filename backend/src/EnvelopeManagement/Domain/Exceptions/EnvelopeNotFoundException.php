<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Exceptions;

final class EnvelopeNotFoundException extends \Exception
{
    public const string MESSAGE = 'Envelope not found';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
