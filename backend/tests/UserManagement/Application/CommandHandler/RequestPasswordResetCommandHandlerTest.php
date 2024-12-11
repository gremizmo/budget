<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\CommandHandler;

use App\UserManagement\Application\Command\RequestPasswordResetCommand;
use App\UserManagement\Application\CommandHandler\RequestPasswordResetCommandHandler;
use App\UserManagement\Domain\Adapter\MailerInterface;
use App\UserManagement\Domain\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Domain\Service\PasswordResetTokenGenerator;
use App\UserManagement\Domain\Service\PasswordResetTokenGeneratorInterface;
use App\UserManagement\Infrastructure\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestPasswordResetCommandHandlerTest extends TestCase
{
    private UserCommandRepositoryInterface&MockObject $userCommandRepository;
    private MailerInterface&MockObject $mailer;
    private PasswordResetTokenGeneratorInterface $passwordResetTokenGenerator;
    private RequestPasswordResetCommandHandler $handler;

    protected function setUp(): void
    {
        $this->userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->passwordResetTokenGenerator = new PasswordResetTokenGenerator();
        $this->handler = new RequestPasswordResetCommandHandler(
            $this->mailer,
            $this->userCommandRepository,
            $this->passwordResetTokenGenerator,
        );
    }

    public function testRequestPasswordResetSuccess(): void
    {
        $user = new User();
        $command = new RequestPasswordResetCommand($user);

        $this->userCommandRepository->expects($this->once())->method('save')->with($user);
        $this->mailer->expects($this->once())->method('sendPasswordResetEmail');

        $this->handler->__invoke($command);

        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getPasswordResetTokenExpiry());
    }
}
