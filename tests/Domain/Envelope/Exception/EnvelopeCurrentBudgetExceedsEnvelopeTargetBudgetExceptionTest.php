<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Domain\Envelope\Exception\CurrentBudgetExceedsEnvelopeTargetBudgetException;
use PHPUnit\Framework\TestCase;

class EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new CurrentBudgetExceedsEnvelopeTargetBudgetException(
            CurrentBudgetExceedsEnvelopeTargetBudgetException::MESSAGE,
            400
        );

        $this->assertSame(
            CurrentBudgetExceedsEnvelopeTargetBudgetException::MESSAGE,
            $exception->getMessage()
        );
    }

    public function testExceptionCode(): void
    {
        $exception = new CurrentBudgetExceedsEnvelopeTargetBudgetException(
            CurrentBudgetExceedsEnvelopeTargetBudgetException::MESSAGE,
            400
        );

        $this->assertSame(400, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new CurrentBudgetExceedsEnvelopeTargetBudgetException(
            CurrentBudgetExceedsEnvelopeTargetBudgetException::MESSAGE,
            400,
            $previous
        );

        $this->assertSame($previous, $exception->getPrevious());
    }
}
