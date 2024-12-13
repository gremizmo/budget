<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Handlers\CommandHandlers;

use App\SharedContext\Domain\Ports\Inbound\EventSourcedRepositoryInterface;
use App\UserManagement\Application\Commands\UpdateUserPasswordCommand;
use App\UserManagement\Domain\Aggregates\User;
use App\UserManagement\Domain\Exceptions\UserOldPasswordIsIncorrectException;
use App\UserManagement\Domain\Ports\Inbound\UserRepositoryInterface;
use App\UserManagement\Domain\Ports\Outbound\PasswordHasherInterface;
use App\UserManagement\Domain\ValueObjects\Password;
use App\UserManagement\Domain\ValueObjects\UserId;

final readonly class UpdateUserPasswordCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @throws UserOldPasswordIsIncorrectException
     */
    public function __invoke(UpdateUserPasswordCommand $command): void
    {
        $events = $this->eventSourcedRepository->get($command->getUuid());
        $aggregate = User::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $userView = $this->userRepository->findOneBy(['uuid' => $command->getUuid()]);

        if (!$this->passwordHasher->verify($userView, $command->getOldPassword())) {
            throw new UserOldPasswordIsIncorrectException(UserOldPasswordIsIncorrectException::MESSAGE, 400);
        }

        $aggregate->updatePassword(
            Password::create(
                $command->getOldPassword(),
            ),
            Password::create(
                $this->passwordHasher->hash($userView, $command->getNewPassword()),
            ),
            UserId::create($command->getUuid()),
        );
        $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
        $aggregate->clearUncommitedEvent();
    }
}
