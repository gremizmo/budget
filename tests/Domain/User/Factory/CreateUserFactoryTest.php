<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Factory;

use App\Application\User\Dto\CreateUserInput;
use App\Domain\User\Adapter\PasswordHasherInterface;
use App\Domain\User\Factory\CreateUserFactory;
use App\Infra\Http\Rest\User\Entity\User;
use PHPUnit\Framework\TestCase;

class CreateUserFactoryTest extends TestCase
{
    public function testCreateUser(): void
    {
        $passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $passwordHasher->method('hash')->willReturn('hashed_password');

        $createUserDto = new CreateUserInput(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $factory = new CreateUserFactory($passwordHasher);
        $user = $factory->createFromDto($createUserDto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John', $user->getFirstname());
        $this->assertEquals('Doe', $user->getLastname());
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('hashed_password', $user->getPassword());
        $this->assertTrue($user->isConsentGiven());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }
}
