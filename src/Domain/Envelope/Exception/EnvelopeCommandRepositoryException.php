<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class EnvelopeCommandRepositoryException extends \Exception
{
    public function __construct(
        string $message = 'An Error occurred in EnvelopeCommandRepository.',
        int $code = 0,
        \Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
