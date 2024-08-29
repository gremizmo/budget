<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class CurrentBudgetExceedsTargetBudgetException extends \Exception
{
    public const MESSAGE = 'Current budget of exceeds target budget';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
