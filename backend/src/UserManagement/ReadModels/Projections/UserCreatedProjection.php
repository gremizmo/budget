<?php

declare(strict_types=1);

namespace App\UserManagement\ReadModels\Projections;

use App\UserManagement\Domain\Events\UserCreatedEvent;
use App\UserManagement\Domain\Ports\Inbound\UserRepositoryInterface;
use App\UserManagement\ReadModels\Views\UserView;

final readonly class UserCreatedProjection
{
    public function __construct(
        private UserRepositoryInterface $userCommandRepository,
    ) {
    }

    public function __invoke(UserCreatedEvent $event): void
    {
        $this->userCommandRepository->save(
            (new UserView())
            ->setUuid($event->getAggregateId())
            ->setCreatedAt($event->occurredOn())
            ->setEmail($event->getEmail())
            ->setFirstName($event->getFirstName())
            ->setLastName($event->getLastName())
            ->setConsentDate($event->occurredOn())
            ->setConsentGiven($event->isConsentGiven())
            ->setRoles($event->getRoles())
            ->setUpdatedAt(\DateTime::createFromImmutable($event->occurredOn()))
            ->setPassword($event->getPassword())
            ->setPasswordResetToken(null)
            ->setPasswordResetTokenExpiry(null)
            ->setIsDeleted(false)
        );
    }
}
