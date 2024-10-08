<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\ChangeUserPasswordCommand;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;

readonly class ChangeUserPasswordCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private PasswordHasherInterface $passwordHasher,
        private LoggerInterface $logger,
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
            $this->logger->error('User old password is incorrect');

            throw new UserOldPasswordIsIncorrectException(UserOldPasswordIsIncorrectException::MESSAGE, 400);
        }

        $hashedPassword = $this->passwordHasher->hash($user, $changePasswordDto->getNewPassword());
        $user->setPassword($hashedPassword);
        $this->userCommandRepository->save($user);
    }
}
