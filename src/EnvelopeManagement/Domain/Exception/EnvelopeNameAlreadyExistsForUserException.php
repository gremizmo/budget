<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Exception;

class EnvelopeNameAlreadyExistsForUserException extends \LogicException
{
    public const MESSAGE = 'Envelope with this name already exists';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
