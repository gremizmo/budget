<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\ResetUserPasswordCommand;
use App\Domain\User\Adapter\PasswordHasherInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;

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
