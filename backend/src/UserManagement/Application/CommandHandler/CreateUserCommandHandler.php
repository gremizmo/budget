<?php

declare(strict_types=1);

namespace App\UserManagement\Application\CommandHandler;

use App\UserManagement\Application\Command\CreateUserCommand;
use App\UserManagement\Domain\Factory\CreateUserFactoryInterface;
use App\UserManagement\Domain\Repository\UserCommandRepositoryInterface;

readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private CreateUserFactoryInterface $userFactory,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $this->userCommandRepository->save($this->userFactory->createFromDto($command->getCreateUserDto()));
    }
}
