<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use PHPUnit\Framework\TestCase;

class ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(
            ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            0
        );

        $this->assertSame(
            ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            $exception->getMessage()
        );
    }

    public function testExceptionCode(): void
    {
        $exception = new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(
            ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            123
        );

        $this->assertSame(123, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(
            ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            0,
            $previous
        );

        $this->assertSame($previous, $exception->getPrevious());
    }
}
