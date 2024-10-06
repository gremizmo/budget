<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Exception;

class EnvelopeTitleAlreadyExistsForUserException extends \Exception
{
    public const MESSAGE = 'Envelope with this title already exists';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
