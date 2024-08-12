<?php

declare(strict_types=1);

namespace App\Tests\Infra\Http\Rest\User\Adapter;

use App\Infra\Http\Rest\User\Adapter\PasswordHasherAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class PasswordHasherAdapterTest extends TestCase
{
    public function testHash(): void
    {
        $user = $this->createMock(PasswordAuthenticatedUserInterface::class);
        $password = 'password123';
        $hashedPassword = 'hashed_password123';

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($user, $password)
            ->willReturn($hashedPassword);

        $adapter = new PasswordHasherAdapter($passwordHasher);
        $result = $adapter->hash($user, $password);

        $this->assertSame($hashedPassword, $result);
    }
}
