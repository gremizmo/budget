<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Exception;

class InvalidEnvelopeOperationException extends \LogicException
{
    private function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function operationOnDeletedEnvelope(): self
    {
        return new self(
            'Cannot modify a deleted envelope.',
            400,
        );
    }
}
