<?php

declare(strict_types=1);

namespace App\UserManagement\Application\CommandHandler;

use App\UserManagement\Application\Command\EditUserCommand;
use App\UserManagement\Domain\Factory\EditUserFactoryInterface;
use App\UserManagement\Domain\Repository\UserCommandRepositoryInterface;

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
