<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Factory;

class CreateEnvelopeFactoryException extends \Exception
{
    public const MESSAGE = 'An error occurred while creating an envelope in CreateEnvelopeFactory';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
