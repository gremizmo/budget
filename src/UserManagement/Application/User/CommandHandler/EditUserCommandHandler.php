<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\EditUserCommand;
use App\UserManagement\Domain\User\Factory\EditUserFactoryInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;

readonly class EditUserCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private EditUserFactoryInterface $editUserFactory,
    ) {
    }

    public function __invoke(EditUserCommand $editUserCommand): void
    {
        $this->userCommandRepository->save(
            $this->editUserFactory->createFromDto(
                $editUserCommand->getUser(),
                $editUserCommand->getEditUserDTO()
            )
        );
    }
}
