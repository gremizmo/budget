<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Exception;

use App\UserManagement\Application\User\CommandHandler\UserOldPasswordIsIncorrectException;
use PHPUnit\Framework\TestCase;

class UserOldPasswordIsIncorrectExceptionTest extends TestCase
{
    public function testUserOldPasswordIsIncorrectException(): void
    {
        $message = UserOldPasswordIsIncorrectException::MESSAGE;
        $code = 400;

        $exception = new UserOldPasswordIsIncorrectException($message, $code);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }
}
