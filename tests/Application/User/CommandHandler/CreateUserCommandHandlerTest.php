<?php

declare(strict_types=1);

namespace App\Tests\Application\User\CommandHandler;

use App\Application\User\Command\CreateUserCommand;
use App\Application\User\CommandHandler\CreateUserCommandHandler;
use App\Domain\User\Dto\CreateUserDto;
use App\Domain\User\Entity\User;
use App\Domain\User\Factory\CreateUserFactoryInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $createUserDto = new CreateUserDto(
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
            ->method('createUser')
            ->with($createUserDto)
            ->willReturn($user);

        $userCommandRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $handler = new CreateUserCommandHandler($userCommandRepository, $userFactory);
        $handler($command);
    }
}
