<?php

declare(strict_types=1);

namespace App\UserManagement\ReadModels\Projections;

use App\UserManagement\Domain\Events\UserLastnameUpdatedEvent;
use App\UserManagement\Domain\Ports\Inbound\UserRepositoryInterface;
use App\UserManagement\Domain\Ports\Inbound\UserViewInterface;

final readonly class UserLastnameUpdatedProjection
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(UserLastnameUpdatedEvent $event): void
    {
        $user = $this->userRepository->findOneBy(
            ['uuid' => $event->getAggregateId(), 'isDeleted' => false],
        );

        if (!$user instanceof UserViewInterface) {
            return;
        }

        $user->setUpdatedAt(\DateTime::createFromImmutable($event->occurredOn()));
        $user->setLastname($event->getLastname());
        $this->userRepository->save($user);
    }
}
