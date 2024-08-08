<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Domain\Envelope\Exception\SelfParentEnvelopeException;
use PHPUnit\Framework\TestCase;

class SelfParentEnvelopeExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new SelfParentEnvelopeException(
            'test SelfParentEnvelopeException message',
            0
        );

        $this->assertSame(
            'test SelfParentEnvelopeException message',
            $exception->getMessage()
        );
    }

    public function testExceptionCode(): void
    {
        $exception = new SelfParentEnvelopeException(
            'test SelfParentEnvelopeException message',
            123
        );

        $this->assertSame(123, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new SelfParentEnvelopeException(
            'test SelfParentEnvelopeException message',
            0,
            $previous
        );

        $this->assertSame($previous, $exception->getPrevious());
    }
}
