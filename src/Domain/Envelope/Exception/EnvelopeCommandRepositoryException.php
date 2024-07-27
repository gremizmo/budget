<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class EnvelopeCommandRepositoryException extends \Exception
{
    public const MESSAGE = 'An Error occurred in EnvelopeCommandRepository';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
