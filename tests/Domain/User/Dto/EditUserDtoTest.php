<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Dto;

use App\Domain\User\Dto\EditUserDto;
use PHPUnit\Framework\TestCase;

class EditUserDtoTest extends TestCase
{
    public function testEditUserDtoInstantiation(): void
    {
        $dto = new EditUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $this->assertInstanceOf(EditUserDto::class, $dto);
    }

    public function testGetEmail(): void
    {
        $dto = new EditUserDto(
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
        $dto = new EditUserDto(
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
        $dto = new EditUserDto(
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
        $dto = new EditUserDto(
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
        $dto = new EditUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $this->assertTrue($dto->isConsentGiven());
    }
}
