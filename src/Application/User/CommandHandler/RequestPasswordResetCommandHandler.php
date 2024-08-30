<?php

declare(strict_types=1);

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\RequestPasswordResetCommand;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\Shared\Adapter\MailerInterface;
use App\Domain\User\Repository\UserCommandRepositoryInterface;
use App\Domain\User\Service\PasswordResetTokenGeneratorInterface;

readonly class RequestPasswordResetCommandHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private UserCommandRepositoryInterface $userCommandRepository,
        private PasswordResetTokenGeneratorInterface $passwordResetTokenGenerator,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws RequestPasswordResetCommandHandlerException
     */
    public function __invoke(RequestPasswordResetCommand $command): void
    {
        try {
            $user = $command->getUser();
            $token = $this->passwordResetTokenGenerator->generate();
            $user->setPasswordResetToken($token);
            $user->setPasswordResetTokenExpiry(new \DateTimeImmutable('+1 hour'));
            $this->userCommandRepository->save($user);
            $this->mailer->sendPasswordResetEmail($user, $token);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new RequestPasswordResetCommandHandlerException(RequestPasswordResetCommandHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
