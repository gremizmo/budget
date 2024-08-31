<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Exception;

class ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException extends \Exception
{
    public const MESSAGE = 'Total target budget of children envelopes exceeds the parent envelope\'s target budget';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
