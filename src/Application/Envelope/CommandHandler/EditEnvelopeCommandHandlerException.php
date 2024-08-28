<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

class EditEnvelopeCommandHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while editing an envelope in EditEnvelopeCommandHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
