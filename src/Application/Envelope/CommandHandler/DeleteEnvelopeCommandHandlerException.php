<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

class DeleteEnvelopeCommandHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while deleting an envelope in DeleteEnvelopeCommandHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
