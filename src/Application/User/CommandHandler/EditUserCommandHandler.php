<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\EditUserCommand;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Factory\EditUserFactoryInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;

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
