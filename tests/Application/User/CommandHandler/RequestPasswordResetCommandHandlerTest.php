<?php

declare(strict_types=1);

namespace App\Tests\Application\User\CommandHandler;

use App\Application\User\Command\RequestPasswordResetCommand;
use App\Application\User\CommandHandler\RequestPasswordResetCommandHandler;
use App\Domain\Shared\Adapter\MailerInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserCommandRepositoryInterface;
use App\Domain\User\Service\PasswordResetTokenGeneratorInterface;
use PHPUnit\Framework\TestCase;

class RequestPasswordResetCommandHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $passwordResetTokenGenerator = $this->createMock(PasswordResetTokenGeneratorInterface::class);
        $user = $this->createMock(User::class);

        $command = new RequestPasswordResetCommand($user);
        $handler = new RequestPasswordResetCommandHandler($mailer, $userCommandRepository, $passwordResetTokenGenerator);

        $token = 'reset-token';
        $passwordResetTokenGenerator->expects($this->once())
            ->method('generate')
            ->willReturn($token);

        $user->expects($this->once())
            ->method('setPasswordResetToken')
            ->with($token);

        $user->expects($this->once())
            ->method('setPasswordResetTokenExpiry')
            ->with($this->isInstanceOf(\DateTimeImmutable::class));

        $userCommandRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $mailer->expects($this->once())
            ->method('sendPasswordResetEmail')
            ->with($user, $token);

        $handler($command);
    }
}
