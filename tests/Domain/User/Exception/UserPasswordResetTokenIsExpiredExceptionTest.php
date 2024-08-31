<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Exception;

use App\BudgetManagement\Infrastructure\Http\Rest\Envelope\Exception\UserPasswordResetTokenIsExpiredException;
use PHPUnit\Framework\TestCase;

class UserPasswordResetTokenIsExpiredExceptionTest extends TestCase
{
    public function testConstructorSetsProperties(): void
    {
        $message = 'Token expired';
        $code = 123;
        $previous = new \Exception('Previous exception');

        $exception = new UserPasswordResetTokenIsExpiredException($message, $code, $previous);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testMessageConstant(): void
    {
        $this->assertSame('User password reset token is expired', UserPasswordResetTokenIsExpiredException::MESSAGE);
    }
}
