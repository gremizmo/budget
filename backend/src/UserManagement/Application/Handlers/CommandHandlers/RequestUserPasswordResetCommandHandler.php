<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Handlers\CommandHandlers;

use App\SharedContext\Domain\Ports\Inbound\EventSourcedRepositoryInterface;
use App\UserManagement\Application\Commands\RequestUserPasswordResetCommand;
use App\UserManagement\Domain\Aggregates\User;
use App\UserManagement\Domain\Exceptions\UserNotFoundException;
use App\UserManagement\Domain\Ports\Inbound\PasswordResetTokenGeneratorInterface;
use App\UserManagement\Domain\Ports\Inbound\UserRepositoryInterface;
use App\UserManagement\Domain\ValueObjects\Password;
use App\UserManagement\Domain\ValueObjects\UserId;

final readonly class RequestUserPasswordResetCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordResetTokenGeneratorInterface $passwordResetTokenGenerator,
        private EventSourcedRepositoryInterface $eventSourcedRepository,
    ) {
    }

    public function __invoke(RequestUserPasswordResetCommand $command): void
    {
        $userView = $this->userRepository->findOneBy(['email' => $command->getEmail(), 'isDeleted' => false]);

        if (!$userView) {
            throw new UserNotFoundException(UserNotFoundException::MESSAGE, 404);
        }

        $events = $this->eventSourcedRepository->get($userView->getUuid());
        $aggregate = User::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $aggregate->setPasswordResetToken(
            Password::create(
                $this->passwordResetTokenGenerator->generate(),
            ),
            UserId::create($userView->getUuid()),
        );
        $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
        $aggregate->clearUncommitedEvent();
    }
}
