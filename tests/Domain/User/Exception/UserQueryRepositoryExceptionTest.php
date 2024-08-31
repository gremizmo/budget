<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Exception;

use App\UserManagement\Infrastructure\User\Repository\UserQueryRepositoryException;
use PHPUnit\Framework\TestCase;

class UserQueryRepositoryExceptionTest extends TestCase
{
    public function testExceptionInstantiation(): void
    {
        $exception = new UserQueryRepositoryException(
            message: UserQueryRepositoryException::MESSAGE,
            code: 500,
            previous: null
        );

        $this->assertInstanceOf(UserQueryRepositoryException::class, $exception);
    }

    public function testGetMessage(): void
    {
        $exception = new UserQueryRepositoryException(
            message: UserQueryRepositoryException::MESSAGE,
            code: 500,
            previous: null
        );

        $this->assertEquals(UserQueryRepositoryException::MESSAGE, $exception->getMessage());
    }

    public function testGetCode(): void
    {
        $exception = new UserQueryRepositoryException(
            message: UserQueryRepositoryException::MESSAGE,
            code: 500,
            previous: null
        );

        $this->assertEquals(500, $exception->getCode());
    }

    public function testGetPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $exception = new UserQueryRepositoryException(
            message: UserQueryRepositoryException::MESSAGE,
            code: 500,
            previous: $previousException
        );

        $this->assertSame($previousException, $exception->getPrevious());
    }
}
