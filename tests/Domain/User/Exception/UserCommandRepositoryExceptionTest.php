<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Exception;

use App\UserManagement\Infrastructure\User\Repository\UserCommandRepositoryException;
use PHPUnit\Framework\TestCase;

class UserCommandRepositoryExceptionTest extends TestCase
{
    public function testExceptionInstantiation(): void
    {
        $exception = new UserCommandRepositoryException(
            message: UserCommandRepositoryException::MESSAGE,
            code: 500,
            previous: null
        );

        $this->assertInstanceOf(UserCommandRepositoryException::class, $exception);
    }

    public function testGetMessage(): void
    {
        $exception = new UserCommandRepositoryException(
            message: UserCommandRepositoryException::MESSAGE,
            code: 500,
            previous: null
        );

        $this->assertEquals(UserCommandRepositoryException::MESSAGE, $exception->getMessage());
    }

    public function testGetCode(): void
    {
        $exception = new UserCommandRepositoryException(
            message: UserCommandRepositoryException::MESSAGE,
            code: 500,
            previous: null
        );

        $this->assertEquals(500, $exception->getCode());
    }

    public function testGetPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $exception = new UserCommandRepositoryException(
            message: UserCommandRepositoryException::MESSAGE,
            code: 500,
            previous: $previousException
        );

        $this->assertSame($previousException, $exception->getPrevious());
    }
}
