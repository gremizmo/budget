<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Exceptions;

final class EnvelopeAlreadyExistsException extends \LogicException
{
    public const MESSAGE = 'Envelope already exists';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
