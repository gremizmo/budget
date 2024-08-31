<?php

declare(strict_types=1);

namespace App\Tests\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\ResetUserPasswordCommand;
use App\UserManagement\Application\User\CommandHandler\ResetPasswordCommandHandler;
use App\UserManagement\Application\User\Dto\ResetUserPasswordInputInterface;
use App\UserManagement\Domain\Shared\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class ResetPasswordCommandHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $resetUserPasswordDto = $this->createMock(ResetUserPasswordInputInterface::class);
        $user = $this->createMock(User::class);

        $newPassword = 'new-password';
        $hashedPassword = 'hashed-password';
        $command = new ResetUserPasswordCommand($resetUserPasswordDto, $user);
        $handler = new ResetPasswordCommandHandler($userCommandRepository, $passwordHasher);

        $resetUserPasswordDto->expects($this->once())
            ->method('getNewPassword')
            ->willReturn($newPassword);

        $passwordHasher->expects($this->once())
            ->method('hash')
            ->with($user, $newPassword)
            ->willReturn($hashedPassword);

        $user->expects($this->once())
            ->method('setPassword')
            ->with($hashedPassword);

        $userCommandRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $handler($command);
    }
}
