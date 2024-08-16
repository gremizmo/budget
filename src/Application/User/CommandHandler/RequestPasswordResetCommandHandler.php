<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\RequestPasswordResetCommand;
use App\Domain\Shared\Adapter\MailerInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;
use App\Domain\User\Service\PasswordResetTokenGeneratorInterface;

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
        $expiry = new \DateTimeImmutable('+1 hour');

        $user->setPasswordResetToken($token);
        $user->setPasswordResetTokenExpiry($expiry);

        $this->userCommandRepository->save($user);

        $this->mailer->sendPasswordResetEmail($user, $token);
    }
}
