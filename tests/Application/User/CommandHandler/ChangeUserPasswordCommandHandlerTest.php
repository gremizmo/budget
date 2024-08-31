<?php

declare(strict_types=1);

namespace App\Tests\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\ChangeUserPasswordCommand;
use App\UserManagement\Application\User\CommandHandler\ChangeUserPasswordCommandHandler;
use App\UserManagement\Application\User\CommandHandler\UserOldPasswordIsIncorrectException;
use App\UserManagement\Application\User\Dto\ChangeUserPasswordInputInterface;
use App\UserManagement\Domain\Shared\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class ChangeUserPasswordCommandHandlerTest extends TestCase
{
    /**
     * @throws UserOldPasswordIsIncorrectException
     */
    public function testSuccessfulPasswordChange(): void
    {
        $changePasswordDto = $this->createMock(ChangeUserPasswordInputInterface::class);
        $user = new User();
        $userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $passwordHasher = $this->createMock(PasswordHasherInterface::class);

        $changePasswordDto->method('getOldPassword')->willReturn('oldPassword');
        $changePasswordDto->method('getNewPassword')->willReturn('newPassword');
        $passwordHasher->method('verify')->willReturn(true);
        $passwordHasher->method('hash')->willReturn('hashedNewPassword');

        $userCommandRepository->expects($this->once())->method('save')->with($user);

        $command = new ChangeUserPasswordCommand($changePasswordDto, $user);
        $handler = new ChangeUserPasswordCommandHandler($userCommandRepository, $passwordHasher);

        $handler->__invoke($command);
    }

    /**
     * @throws UserOldPasswordIsIncorrectException
     */
    public function testPasswordChangeWithIncorrectOldPassword(): void
    {
        $this->expectException(UserOldPasswordIsIncorrectException::class);

        $changePasswordDto = $this->createMock(ChangeUserPasswordInputInterface::class);
        $user = new User();
        $userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $passwordHasher = $this->createMock(PasswordHasherInterface::class);

        $changePasswordDto->method('getOldPassword')->willReturn('oldPassword');
        $passwordHasher->method('verify')->willReturn(false);

        $command = new ChangeUserPasswordCommand($changePasswordDto, $user);
        $handler = new ChangeUserPasswordCommandHandler($userCommandRepository, $passwordHasher);

        $handler->__invoke($command);
    }
}
