<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Exception;

class ChildrenCurrentBudgetExceedsCurrentBudgetException extends \Exception
{
    public const MESSAGE = 'Total current budget of children envelopes exceeds the envelope\'s current budget';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
