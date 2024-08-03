<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Domain\Envelope\Exception\EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException;
use PHPUnit\Framework\TestCase;

class ChildrenTargetBudgetsExceedsParentExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException(
            EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            0
        );

        $this->assertSame(
            EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            $exception->getMessage()
        );
    }

    public function testExceptionCode(): void
    {
        $exception = new EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException(
            EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            123
        );

        $this->assertSame(123, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException(
            EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            0,
            $previous
        );

        $this->assertSame($previous, $exception->getPrevious());
    }
}
