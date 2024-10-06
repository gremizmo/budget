<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\EditUserCommand;
use App\UserManagement\Application\User\CommandHandler\EditUserCommandHandler;
use App\UserManagement\Application\User\Dto\EditUserInput;
use App\UserManagement\Domain\User\Factory\EditUserFactory;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EditUserCommandHandlerTest extends TestCase
{
    private UserCommandRepositoryInterface&MockObject $userCommandRepository;
    private EditUserCommandHandler $handler;
    private EditUserFactory $editUserFactory;

    protected function setUp(): void
    {
        $this->userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $this->editUserFactory = new EditUserFactory();
        $this->handler = new EditUserCommandHandler(
            $this->userCommandRepository,
            $this->editUserFactory,
        );
    }

    public function testEditUserSuccess(): void
    {
        $user = new User();
        $editUserInput = new EditUserInput('John', 'Doe');
        $command = new EditUserCommand($user, $editUserInput);

        $this->userCommandRepository->expects($this->once())->method('save')->with($user);

        $this->handler->__invoke($command);

        $this->assertEquals('John', $user->getFirstname());
        $this->assertEquals('Doe', $user->getLastname());
    }
}
