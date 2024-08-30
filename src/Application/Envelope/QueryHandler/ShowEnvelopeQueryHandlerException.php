<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

class ShowEnvelopeQueryHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while getting an envelope in ShowEnvelopeQueryHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
