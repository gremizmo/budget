<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException extends \Exception
{
    public const MESSAGE = 'Total current budget of children envelopes exceeds the current envelope\'s current budget';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
