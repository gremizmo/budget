<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException extends \Exception
{
    public const MESSAGE = 'Current budget of envelope exceeds the envelope\'s target budget';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
