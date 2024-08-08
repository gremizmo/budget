<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use PHPUnit\Framework\TestCase;

class EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(
            EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            0
        );

        $this->assertSame(
            EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            $exception->getMessage()
        );
    }

    public function testExceptionCode(): void
    {
        $exception = new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(
            EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            123
        );

        $this->assertSame(123, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(
            EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
            0,
            $previous
        );

        $this->assertSame($previous, $exception->getPrevious());
    }
}
