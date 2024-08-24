<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\CreateUserCommand;
use App\Domain\User\Factory\CreateUserFactoryInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;

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
