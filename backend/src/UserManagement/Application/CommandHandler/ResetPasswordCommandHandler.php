<?php

declare(strict_types=1);

namespace App\UserManagement\Application\CommandHandler;

use App\UserManagement\Application\Command\ResetUserPasswordCommand;
use App\UserManagement\Domain\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\Repository\UserCommandRepositoryInterface;

readonly class ResetPasswordCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(ResetUserPasswordCommand $command): void
    {
        $user = $command->getUser();
        $user->setPassword($this->passwordHasher->hash($user, $command->getResetUserPasswordDto()->getNewPassword()));
        $this->userCommandRepository->save($user);
    }
}
