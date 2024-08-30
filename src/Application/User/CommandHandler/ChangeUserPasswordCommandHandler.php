<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\ChangeUserPasswordCommand;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Adapter\PasswordHasherInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;

readonly class ChangeUserPasswordCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private PasswordHasherInterface $passwordHasher,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws ChangeUserPasswordCommandHandlerException
     */
    public function __invoke(ChangeUserPasswordCommand $command): void
    {
        $user = $command->getUser();
        $changePasswordDto = $command->getChangePasswordDto();
        try {
            if (!$this->passwordHasher->verify($user, $changePasswordDto->getOldPassword())) {
                throw new UserOldPasswordIsIncorrectException(UserOldPasswordIsIncorrectException::MESSAGE, 400);
            }

            $hashedPassword = $this->passwordHasher->hash($user, $changePasswordDto->getNewPassword());
            $user->setPassword($hashedPassword);
            $this->userCommandRepository->save($user);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new ChangeUserPasswordCommandHandlerException(ChangeUserPasswordCommandHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
