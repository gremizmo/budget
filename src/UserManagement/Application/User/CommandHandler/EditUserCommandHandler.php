<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\EditUserCommand;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Factory\EditUserFactoryInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;

readonly class EditUserCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private EditUserFactoryInterface $editUserFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws EditUserCommandHandlerException
     */
    public function __invoke(EditUserCommand $editUserCommand): void
    {
        try {
            $this->userCommandRepository->save(
                $this->editUserFactory->createFromDto(
                    $editUserCommand->getUser(),
                    $editUserCommand->getEditUserDTO()
                )
            );
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new EditUserCommandHandlerException(EditUserCommandHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
