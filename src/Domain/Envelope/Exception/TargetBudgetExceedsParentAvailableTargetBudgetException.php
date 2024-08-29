<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class TargetBudgetExceedsParentAvailableTargetBudgetException extends \Exception
{
    public const MESSAGE = 'Target budget exceeds parent available target budget';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
