<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\QueryHandler;

class GetEnvelopeByTitleQueryHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while getting an envelope by title in GetEnvelopeByTitleQueryHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
