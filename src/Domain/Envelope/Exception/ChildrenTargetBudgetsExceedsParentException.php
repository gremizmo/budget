<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Exception;

class ChildrenTargetBudgetsExceedsParentException extends \Exception
{
    public function __construct(
        string $message = 'Children target budgets exceed parent.',
        int $code = 0,
        \Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
