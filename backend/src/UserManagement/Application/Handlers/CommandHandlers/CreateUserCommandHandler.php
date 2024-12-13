<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Handlers\CommandHandlers;

use App\SharedContext\Domain\Ports\Inbound\EventSourcedRepositoryInterface;
use App\UserManagement\Application\Commands\CreateUserCommand;
use App\UserManagement\Domain\Aggregates\User;
use App\UserManagement\Domain\Exceptions\UserAlreadyExistsException;
use App\UserManagement\Domain\Ports\Inbound\UserRepositoryInterface;
use App\UserManagement\Domain\Ports\Outbound\PasswordHasherInterface;
use App\UserManagement\ReadModels\Views\UserView;

final readonly class CreateUserCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        try {
            $this->eventSourcedRepository->get($command->getUuid());
        } catch (\RuntimeException $exception) {
            $aggregate = User::create(
                $command->getUuid(),
                $command->getEmail(),
                $this->userPasswordHasher->hash(new UserView(), $command->getPassword()),
                $command->getFirstName(),
                $command->getLastName(),
                $command->isConsentGiven(),
                $this->userRepository,
            );
            $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
            $aggregate->clearUncommitedEvent();

            return;
        }

        throw new UserAlreadyExistsException(UserAlreadyExistsException::MESSAGE, 400);
    }
}
