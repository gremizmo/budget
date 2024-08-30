<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

class CreateEnvelopeBuilderException extends \Exception
{
    public const MESSAGE = 'An error occurred while creating an envelope in CreateEnvelopeBuilder';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
