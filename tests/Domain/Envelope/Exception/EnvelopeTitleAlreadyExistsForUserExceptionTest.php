<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Exception;

use App\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use PHPUnit\Framework\TestCase;

class EnvelopeTitleAlreadyExistsForUserExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new EnvelopeTitleAlreadyExistsForUserException(
            EnvelopeTitleAlreadyExistsForUserException::MESSAGE,
            0
        );
        $this->assertEquals(EnvelopeTitleAlreadyExistsForUserException::MESSAGE, $exception->getMessage());
    }

    public function testExceptionCode(): void
    {
        $code = 123;
        $exception = new EnvelopeTitleAlreadyExistsForUserException(
            EnvelopeTitleAlreadyExistsForUserException::MESSAGE,
            $code
        );
        $this->assertEquals($code, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new EnvelopeTitleAlreadyExistsForUserException(
            EnvelopeTitleAlreadyExistsForUserException::MESSAGE,
            0,
            $previous
        );
        $this->assertSame($previous, $exception->getPrevious());
    }
}
