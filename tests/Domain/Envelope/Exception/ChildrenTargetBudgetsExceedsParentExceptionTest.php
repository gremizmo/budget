<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentException;
use PHPUnit\Framework\TestCase;

class ChildrenTargetBudgetsExceedsParentExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new ChildrenTargetBudgetsExceedsParentException(
            ChildrenTargetBudgetsExceedsParentException::MESSAGE,
            0
        );

        $this->assertSame(
            ChildrenTargetBudgetsExceedsParentException::MESSAGE,
            $exception->getMessage()
        );
    }

    public function testExceptionCode(): void
    {
        $exception = new ChildrenTargetBudgetsExceedsParentException(
            ChildrenTargetBudgetsExceedsParentException::MESSAGE,
            123
        );

        $this->assertSame(123, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new ChildrenTargetBudgetsExceedsParentException(
            ChildrenTargetBudgetsExceedsParentException::MESSAGE,
            0,
            $previous
        );

        $this->assertSame($previous, $exception->getPrevious());
    }
}
