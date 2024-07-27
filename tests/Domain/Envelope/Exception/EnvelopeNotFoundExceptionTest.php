<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Domain\Envelope\Exception\EnvelopeNotFoundException;
use PHPUnit\Framework\TestCase;

class EnvelopeNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new EnvelopeNotFoundException(
            EnvelopeNotFoundException::MESSAGE,
            0
        );

        $this->assertSame(
            EnvelopeNotFoundException::MESSAGE,
            $exception->getMessage()
        );
    }

    public function testExceptionCode(): void
    {
        $exception = new EnvelopeNotFoundException(
            EnvelopeNotFoundException::MESSAGE,
            123
        );

        $this->assertSame(123, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new EnvelopeNotFoundException(
            EnvelopeNotFoundException::MESSAGE,
            0,
            $previous
        );

        $this->assertSame($previous, $exception->getPrevious());
    }
}
