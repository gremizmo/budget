<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\CommandHandler;

use App\UserManagement\Application\Command\CreateUserCommand;
use App\UserManagement\Application\CommandHandler\CreateUserCommandHandler;
use App\UserManagement\Application\Dto\CreateUserInput;
use App\UserManagement\Domain\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\Adapter\UuidAdapterInterface;
use App\UserManagement\Domain\Factory\CreateUserFactory;
use App\UserManagement\Domain\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerTest extends TestCase
{
    private UserCommandRepositoryInterface&MockObject $userCommandRepository;
    private UuidAdapterInterface&MockObject $uuidAdapter;
    private PasswordHasherInterface&MockObject $passwordHasher;
    private CreateUserFactory $createUserFactory;
    private CreateUserCommandHandler $handler;

    protected function setUp(): void
    {
        $this->userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
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
        );
    }

    public function testCreateUserSuccess(): void
    {
        $createUserInput = new CreateUserInput('test@example.com', 'password', 'John', 'Doe', true);
        $command = new CreateUserCommand($createUserInput);

        $this->passwordHasher->method('hash')->willReturn('hashed-new-password');
        $this->uuidAdapter->method('generate')->willReturn('uuid');

        $this->userCommandRepository->expects($this->once())->method('save');

        $this->handler->__invoke($command);
    }
}
