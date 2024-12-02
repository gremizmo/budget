<?php

declare(strict_types=1);

namespace App\UserManagement\Application\CommandHandler;

use App\UserManagement\Application\Command\ChangeUserPasswordCommand;
use App\UserManagement\Domain\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\Repository\UserCommandRepositoryInterface;

readonly class ChangeUserPasswordCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @throws UserOldPasswordIsIncorrectException
     */
    public function __invoke(ChangeUserPasswordCommand $command): void
    {
        $user = $command->getUser();
        $changePasswordDto = $command->getChangePasswordDto();
        if (!$this->passwordHasher->verify($user, $changePasswordDto->getOldPassword())) {
            throw new UserOldPasswordIsIncorrectException(UserOldPasswordIsIncorrectException::MESSAGE, 400);
        }

        $hashedPassword = $this->passwordHasher->hash($user, $changePasswordDto->getNewPassword());
        $user->setPassword($hashedPassword);
        $this->userCommandRepository->save($user);
    }
}
