<?php

declare(strict_types=1);

namespace App\Tests\Application\User\Command;

use App\Application\User\Command\CreateUserCommand;
use App\Domain\User\Dto\CreateUserDto;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testCommandInstantiation(): void
    {
        $createUserDto = new CreateUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $command = new CreateUserCommand($createUserDto);

        $this->assertInstanceOf(CreateUserCommand::class, $command);
    }

    public function testGetCreateUserDto(): void
    {
        $createUserDto = new CreateUserDto(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $command = new CreateUserCommand($createUserDto);

        $this->assertSame($createUserDto, $command->getCreateUserDto());
    }
}