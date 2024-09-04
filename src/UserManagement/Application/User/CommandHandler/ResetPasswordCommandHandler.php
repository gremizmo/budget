<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\ResetUserPasswordCommand;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Adapter\PasswordHasherInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;

readonly class ResetPasswordCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private PasswordHasherInterface $passwordHasher,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws ResetPasswordCommandHandlerException
     */
    public function __invoke(ResetUserPasswordCommand $command): void
    {
        try {
            $user = $command->getUser();
            $user->setPassword($this->passwordHasher->hash($user, $command->getResetUserPasswordDto()->getNewPassword()));
            $this->userCommandRepository->save($user);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new ResetPasswordCommandHandlerException(ResetPasswordCommandHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
