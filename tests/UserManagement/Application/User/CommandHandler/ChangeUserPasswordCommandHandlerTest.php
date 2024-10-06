<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\ChangeUserPasswordCommand;
use App\UserManagement\Application\User\CommandHandler\ChangeUserPasswordCommandHandler;
use App\UserManagement\Application\User\CommandHandler\UserOldPasswordIsIncorrectException;
use App\UserManagement\Application\User\Dto\ChangeUserPasswordInput;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChangeUserPasswordCommandHandlerTest extends TestCase
{
    private UserCommandRepositoryInterface&MockObject $userCommandRepository;
    private ChangeUserPasswordCommandHandler $handler;
    private PasswordHasherInterface&MockObject $passwordHasher;
    private LoggerInterface&MockObject $logger;

    protected function setUp(): void
    {
        $this->userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $this->passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new ChangeUserPasswordCommandHandler(
            $this->userCommandRepository,
            $this->passwordHasher,
            $this->logger
        );
    }

    public function testChangePasswordSuccess(): void
    {
        $user = new User();
        $user->setPassword('hashed-correct-old-password');
        $changePasswordInput = new ChangeUserPasswordInput('hashed-correct-old-password', 'new-password');
        $command = new ChangeUserPasswordCommand($changePasswordInput, $user);

        $this->passwordHasher->method('verify')->with($user, 'hashed-correct-old-password')->willReturn(true);
        $this->passwordHasher->method('hash')->with($user, 'new-password')->willReturn('hashed-new-password');
        $this->userCommandRepository->expects($this->once())->method('save')->with($user);

        $this->handler->__invoke($command);

        $this->assertEquals('hashed-new-password', $user->getPassword());
    }

    public function testChangePasswordOldPasswordIncorrect(): void
    {
        $this->expectException(UserOldPasswordIsIncorrectException::class);

        $user = new User();
        $user->setPassword('hashed-correct-old-password');
        $changePasswordInput = new ChangeUserPasswordInput('wrong-old-password', 'new-password');
        $command = new ChangeUserPasswordCommand($changePasswordInput, $user);

        $this->passwordHasher->method('verify')->willReturn(false);

        $this->handler->__invoke($command);
    }
}
