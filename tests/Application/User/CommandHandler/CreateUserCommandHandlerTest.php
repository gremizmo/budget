<?php

declare(strict_types=1);

namespace App\Tests\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\CreateUserCommand;
use App\UserManagement\Application\User\CommandHandler\CreateUserCommandHandler;
use App\UserManagement\Application\User\Dto\CreateUserInput;
use App\UserManagement\Domain\User\Factory\CreateUserFactoryInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $createUserDto = new CreateUserInput(
            email: 'test@example.com',
            password: 'password123',
            firstname: 'John',
            lastname: 'Doe',
            consentGiven: true
        );

        $user = new User();
        $command = new CreateUserCommand($createUserDto);

        $userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $userFactory = $this->createMock(CreateUserFactoryInterface::class);

        $userFactory->expects($this->once())
            ->method('createFromDto')
            ->with($createUserDto)
            ->willReturn($user);

        $userCommandRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $handler = new CreateUserCommandHandler($userCommandRepository, $userFactory);
        $handler($command);
    }
}
