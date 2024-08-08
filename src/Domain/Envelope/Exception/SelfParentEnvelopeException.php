<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class SelfParentEnvelopeException extends \Exception
{
    public const MESSAGE = 'Envelope cannot be its own parent.';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
