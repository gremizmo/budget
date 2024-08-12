<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Dto;

use App\Domain\User\Dto\CreateUserDto;
use PHPUnit\Framework\TestCase;

class CreateUserDtoTest extends TestCase
{
    public function testCreateUserDtoInstantiation(): void
    {
        $dto = new CreateUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $this->assertInstanceOf(CreateUserDto::class, $dto);
    }

    public function testGetEmail(): void
    {
        $dto = new CreateUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $this->assertEquals('test@example.com', $dto->getEmail());
    }

    public function testGetPassword(): void
    {
        $dto = new CreateUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $this->assertEquals('password123', $dto->getPassword());
    }

    public function testGetFirstname(): void
    {
        $dto = new CreateUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $this->assertEquals('John', $dto->getFirstname());
    }

    public function testGetLastname(): void
    {
        $dto = new CreateUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $this->assertEquals('Doe', $dto->getLastname());
    }

    public function testIsConsentGiven(): void
    {
        $dto = new CreateUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $this->assertTrue($dto->isConsentGiven());
    }
}
