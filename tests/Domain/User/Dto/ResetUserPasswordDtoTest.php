<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Dto;

use App\UserManagement\Application\User\Dto\ResetUserPasswordInput;
use PHPUnit\Framework\TestCase;

class ResetUserPasswordDtoTest extends TestCase
{
    public function testConstructorSetsProperties(): void
    {
        $token = 'reset-token';
        $newPassword = 'new-password';
        $dto = new ResetUserPasswordInput($token, $newPassword);

        $this->assertSame($token, $dto->getToken());
        $this->assertSame($newPassword, $dto->getNewPassword());
    }

    public function testGetTokenReturnsToken(): void
    {
        $token = 'reset-token';
        $dto = new ResetUserPasswordInput($token, 'new-password');

        $this->assertSame($token, $dto->getToken());
    }

    public function testGetNewPasswordReturnsNewPassword(): void
    {
        $newPassword = 'new-password';
        $dto = new ResetUserPasswordInput('reset-token', $newPassword);

        $this->assertSame($newPassword, $dto->getNewPassword());
    }
}
