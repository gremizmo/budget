<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Exception;

class TargetBudgetExceedsParentMaxAllowableBudgetException extends \Exception
{
    public const MESSAGE = 'Target budget exceeds parent max allowable budget';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
