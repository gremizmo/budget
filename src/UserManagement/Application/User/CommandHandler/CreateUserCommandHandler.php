<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\CreateUserCommand;
use App\UserManagement\Domain\User\Factory\CreateUserFactoryInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;

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
