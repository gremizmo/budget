<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Infra\Http\Rest\Envelope\Repository\EnvelopeCommandRepositoryException;
use PHPUnit\Framework\TestCase;

class EnvelopeCommandRepositoryExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new EnvelopeCommandRepositoryException(
            EnvelopeCommandRepositoryException::MESSAGE,
            0
        );

        $this->assertSame(
            EnvelopeCommandRepositoryException::MESSAGE,
            $exception->getMessage()
        );
    }

    public function testExceptionCode(): void
    {
        $exception = new EnvelopeCommandRepositoryException(
            EnvelopeCommandRepositoryException::MESSAGE,
            123
        );

        $this->assertSame(123, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new EnvelopeCommandRepositoryException(
            EnvelopeCommandRepositoryException::MESSAGE,
            0,
            $previous
        );

        $this->assertSame($previous, $exception->getPrevious());
    }
}
