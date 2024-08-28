<?php

declare(strict_types=1);

namespace App\Tests\Application\User\CommandHandler;

use App\Application\User\Command\EditUserCommand;
use App\Application\User\CommandHandler\EditUserCommandHandler;
use App\Domain\User\Dto\EditUserDto;
use App\Domain\User\Factory\EditUserFactoryInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;
use App\Infra\Http\Rest\User\Entity\User;
use PHPUnit\Framework\TestCase;

class EditUserCommandHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $editUserDto = new EditUserDto(
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
