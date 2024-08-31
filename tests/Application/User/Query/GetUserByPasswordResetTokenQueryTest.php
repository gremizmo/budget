<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Query;

use App\UserManagement\Application\User\Query\GetUserByPasswordResetTokenQuery;
use PHPUnit\Framework\TestCase;

class GetUserByPasswordResetTokenQueryTest extends TestCase
{
    public function testConstructorSetsUserPasswordResetToken(): void
    {
        $token = 'reset-token';
        $query = new GetUserByPasswordResetTokenQuery($token);

        $this->assertSame($token, $query->getUserPasswordResetToken());
    }

    public function testGetUserPasswordResetTokenReturnsToken(): void
    {
        $token = 'reset-token';
        $query = new GetUserByPasswordResetTokenQuery($token);

        $this->assertSame($token, $query->getUserPasswordResetToken());
    }
}
