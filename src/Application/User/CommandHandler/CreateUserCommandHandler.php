<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\CreateUserCommand;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Factory\CreateUserFactoryInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;

readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserCommandRepositoryInterface $userCommandRepository,
        private CreateUserFactoryInterface $userFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws CreateUserCommandHandlerException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        try {
            $this->userCommandRepository->save($this->userFactory->createFromDto($command->getCreateUserDto()));
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new CreateUserCommandHandlerException(CreateUserCommandHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
