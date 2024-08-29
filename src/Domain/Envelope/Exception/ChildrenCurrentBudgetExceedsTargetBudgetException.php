<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class ChildrenCurrentBudgetExceedsTargetBudgetException extends \Exception
{
    public const MESSAGE = 'Total children current budget exceeds the envelope\'s target budget';

    public function __construct(
        string $message,
        int $code,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
