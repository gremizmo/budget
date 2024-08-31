<?php

declare(strict_types=1);

namespace App\Tests\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\EditUserCommand;
use App\UserManagement\Application\User\CommandHandler\EditUserCommandHandler;
use App\UserManagement\Application\User\Dto\EditUserInput;
use App\UserManagement\Domain\User\Factory\EditUserFactoryInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class EditUserCommandHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $editUserDto = new EditUserInput(
            firstname: 'John',
            lastname: 'Doe',
        );

        $user = new User();
        $command = new EditUserCommand($user, $editUserDto);

        $userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $userFactory = $this->createMock(EditUserFactoryInterface::class);

        $userFactory->expects($this->once())
            ->method('createFromDto')
            ->willReturn($user);

        $userCommandRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $handler = new EditUserCommandHandler($userCommandRepository, $userFactory);
        $handler($command);
    }
}
