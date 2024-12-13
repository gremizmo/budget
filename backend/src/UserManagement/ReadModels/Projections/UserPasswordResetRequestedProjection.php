<?php

declare(strict_types=1);

namespace App\UserManagement\ReadModels\Projections;

use App\UserManagement\Domain\Events\UserPasswordResetRequestedEvent;
use App\UserManagement\Domain\Ports\Inbound\UserRepositoryInterface;
use App\UserManagement\Domain\Ports\Inbound\UserViewInterface;
use App\UserManagement\Domain\Ports\Outbound\MailerInterface;

final readonly class UserPasswordResetRequestedProjection
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(UserPasswordResetRequestedEvent $event): void
    {
        $user = $this->userRepository->findOneBy(
            ['uuid' => $event->getAggregateId(), 'isDeleted' => false],
        );

        if (!$user instanceof UserViewInterface) {
            return;
        }

        $resetToken = $event->getPasswordResetToken();
        $user->setUpdatedAt(\DateTime::createFromImmutable($event->occurredOn()));
        $user->setPasswordResetToken($resetToken);
        $user->setPasswordResetTokenExpiry($event->getPasswordResetTokenExpiry());
        $this->userRepository->save($user);
        $this->mailer->sendPasswordResetEmail($user, $resetToken);
    }
}
