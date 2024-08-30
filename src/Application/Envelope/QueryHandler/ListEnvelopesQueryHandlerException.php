<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

class ListEnvelopesQueryHandlerException extends \Exception
{
    public const MESSAGE = 'An error occurred while getting envelopes in ListEnvelopesByTitleQueryHandler';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
