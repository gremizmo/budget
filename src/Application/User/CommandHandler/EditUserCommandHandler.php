<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\EditUserCommand;
use App\Domain\User\Factory\EditUserFactoryInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;

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
