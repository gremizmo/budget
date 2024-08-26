<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception\Builder;

class EditEnvelopeBuilderException extends \Exception
{
    public const MESSAGE = 'An error occurred while creating an envelope in EditEnvelopeBuilder';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
