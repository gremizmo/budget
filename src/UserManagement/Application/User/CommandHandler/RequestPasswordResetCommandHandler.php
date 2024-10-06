<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\RequestPasswordResetCommand;
use App\UserManagement\Domain\User\Adapter\MailerInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Domain\User\Service\PasswordResetTokenGeneratorInterface;

readonly class RequestPasswordResetCommandHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private UserCommandRepositoryInterface $userCommandRepository,
        private PasswordResetTokenGeneratorInterface $passwordResetTokenGenerator,
    ) {
    }

    public function __invoke(RequestPasswordResetCommand $command): void
    {
        $user = $command->getUser();
        $token = $this->passwordResetTokenGenerator->generate();
        $user->setPasswordResetToken($token);
        $user->setPasswordResetTokenExpiry(new \DateTimeImmutable('+1 hour'));
        $this->userCommandRepository->save($user);
        $this->mailer->sendPasswordResetEmail($user, $token);
    }
}
