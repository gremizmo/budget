<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class EnvelopeQueryRepositoryException extends \Exception
{
    public function __construct(
        string $message = 'An Error occurred in EnvelopeQueryRepository.',
        int $code = 0,
        \Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
