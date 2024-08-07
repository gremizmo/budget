<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class EnvelopeInvalidArgumentsException extends \Exception
{
    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
