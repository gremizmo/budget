<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\CreateUserCommand;
use App\UserManagement\Application\User\CommandHandler\CreateUserCommandHandler;
use App\UserManagement\Application\User\CommandHandler\CreateUserCommandHandlerException;
use App\UserManagement\Application\User\Dto\CreateUserInput;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\User\Adapter\UuidAdapterInterface;
use App\UserManagement\Domain\User\Factory\CreateUserFactory;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerTest extends TestCase
{
    private UserCommandRepositoryInterface&MockObject $userCommandRepository;
    private UuidAdapterInterface&MockObject $uuidAdapter;
    private PasswordHasherInterface&MockObject $passwordHasher;
    private LoggerInterface&MockObject $logger;
    private CreateUserFactory $createUserFactory;
    private CreateUserCommandHandler $handler;

    protected function setUp(): void
    {
        $this->userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $this->uuidAdapter = $this->createMock(UuidAdapterInterface::class);
        $this->createUserFactory = new CreateUserFactory(
            $this->passwordHasher,
            $this->uuidAdapter,
            User::class
        );
        $this->handler = new CreateUserCommandHandler(
            $this->userCommandRepository,
            $this->createUserFactory,
            $this->logger
        );
    }

    /**
     * @throws CreateUserCommandHandlerException
     */
    public function testCreateUserSuccess(): void
    {
        $createUserInput = new CreateUserInput('test@example.com', 'password', 'John', 'Doe', true);
        $command = new CreateUserCommand($createUserInput);

        $this->passwordHasher->method('hash')->willReturn('hashed-new-password');
        $this->uuidAdapter->method('generate')->willReturn('uuid');

        $this->userCommandRepository->expects($this->once())->method('save');

        $this->handler->__invoke($command);
    }

    /**
     * @throws CreateUserCommandHandlerException
     */
    public function testCreateUserExceptionDuringProcess(): void
    {
        $this->expectException(CreateUserCommandHandlerException::class);

        $createUserInput = new CreateUserInput('test@example.com', 'password', 'John', 'Doe', true);
        $command = new CreateUserCommand($createUserInput);

        $this->userCommandRepository->method('save')->willThrowException(new \Exception('Save error'));

        $this->handler->__invoke($command);
    }
}
