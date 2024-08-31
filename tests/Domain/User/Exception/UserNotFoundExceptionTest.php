<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Exception;

use App\UserManagement\Application\User\QueryHandler\UserNotFoundException;
use PHPUnit\Framework\TestCase;

class UserNotFoundExceptionTest extends TestCase
{
    public function testExceptionInstantiation(): void
    {
        $exception = new UserNotFoundException(
            message: UserNotFoundException::MESSAGE,
            code: 404,
            previous: null
        );

        $this->assertInstanceOf(UserNotFoundException::class, $exception);
    }

    public function testGetMessage(): void
    {
        $exception = new UserNotFoundException(
            message: UserNotFoundException::MESSAGE,
            code: 404,
            previous: null
        );

        $this->assertEquals(UserNotFoundException::MESSAGE, $exception->getMessage());
    }

    public function testGetCode(): void
    {
        $exception = new UserNotFoundException(
            message: UserNotFoundException::MESSAGE,
            code: 404,
            previous: null
        );

        $this->assertEquals(404, $exception->getCode());
    }

    public function testGetPrevious(): void
    {
        $previousException = new \Exception('Previous exception');
        $exception = new UserNotFoundException(
            message: UserNotFoundException::MESSAGE,
            code: 404,
            previous: $previousException
        );

        $this->assertSame($previousException, $exception->getPrevious());
    }
}
