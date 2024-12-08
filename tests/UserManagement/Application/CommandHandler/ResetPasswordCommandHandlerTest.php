<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\CommandHandler;

use App\UserManagement\Application\Command\ResetUserPasswordCommand;
use App\UserManagement\Application\CommandHandler\ResetPasswordCommandHandler;
use App\UserManagement\Application\Dto\ResetUserPasswordInputInterface;
use App\UserManagement\Domain\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ResetPasswordCommandHandlerTest extends TestCase
{
    private UserCommandRepositoryInterface&MockObject $userCommandRepository;
    private PasswordHasherInterface&MockObject $passwordHasher;
    private ResetPasswordCommandHandler $handler;

    protected function setUp(): void
    {
        $this->userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $this->passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $this->handler = new ResetPasswordCommandHandler(
            $this->userCommandRepository,
            $this->passwordHasher,
        );
    }

    public function testResetPasswordSuccess(): void
    {
        $user = new User();
        $resetUserPasswordDto = $this->createMock(ResetUserPasswordInputInterface::class);
        $resetUserPasswordDto->method('getNewPassword')->willReturn('new-password');
        $command = new ResetUserPasswordCommand($resetUserPasswordDto, $user);

        $this->passwordHasher->method('hash')->with($user, 'new-password')->willReturn('hashed-new-password');
        $this->userCommandRepository->expects($this->once())->method('save')->with($user);

        $this->handler->__invoke($command);

        $this->assertEquals('hashed-new-password', $user->getPassword());
    }
}
