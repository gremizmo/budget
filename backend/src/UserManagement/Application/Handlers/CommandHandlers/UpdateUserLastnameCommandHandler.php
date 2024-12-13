<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Handlers\CommandHandlers;

use App\SharedContext\Domain\Ports\Inbound\EventSourcedRepositoryInterface;
use App\UserManagement\Application\Commands\UpdateUserLastnameCommand;
use App\UserManagement\Domain\Aggregates\User;
use App\UserManagement\Domain\ValueObjects\Lastname;
use App\UserManagement\Domain\ValueObjects\UserId;

final readonly class UpdateUserLastnameCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
    ) {
    }

    public function __invoke(UpdateUserLastnameCommand $command): void
    {
        $events = $this->eventSourcedRepository->get($command->getUuid());
        $aggregate = User::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $aggregate->updateLastname(
            Lastname::create(
                $command->getLastname(),
            ),
            UserId::create($command->getUuid()),
        );
        $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
        $aggregate->clearUncommitedEvent();
    }
}
