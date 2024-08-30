<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Repository;

class EnvelopeQueryRepositoryException extends \Exception
{
    public const MESSAGE = 'An Error occurred in EnvelopeQueryRepository';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
