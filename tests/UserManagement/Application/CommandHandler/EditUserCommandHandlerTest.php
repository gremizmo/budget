<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\CommandHandler;

use App\UserManagement\Application\Command\EditUserCommand;
use App\UserManagement\Application\CommandHandler\EditUserCommandHandler;
use App\UserManagement\Application\Dto\EditUserInput;
use App\UserManagement\Domain\Factory\EditUserFactory;
use App\UserManagement\Domain\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Infrastructure\Entity\User;
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
