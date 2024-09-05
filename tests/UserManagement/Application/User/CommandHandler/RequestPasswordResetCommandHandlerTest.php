<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\User\CommandHandler;

use App\UserManagement\Application\User\Command\RequestPasswordResetCommand;
use App\UserManagement\Application\User\CommandHandler\RequestPasswordResetCommandHandler;
use App\UserManagement\Application\User\CommandHandler\RequestPasswordResetCommandHandlerException;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Adapter\MailerInterface;
use App\UserManagement\Domain\User\Repository\UserCommandRepositoryInterface;
use App\UserManagement\Domain\User\Service\PasswordResetTokenGenerator;
use App\UserManagement\Domain\User\Service\PasswordResetTokenGeneratorInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestPasswordResetCommandHandlerTest extends TestCase
{
    private UserCommandRepositoryInterface&MockObject $userCommandRepository;
    private MailerInterface&MockObject $mailer;
    private PasswordResetTokenGeneratorInterface $passwordResetTokenGenerator;
    private LoggerInterface&MockObject $logger;
    private RequestPasswordResetCommandHandler $handler;

    protected function setUp(): void
    {
        $this->userCommandRepository = $this->createMock(UserCommandRepositoryInterface::class);
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->passwordResetTokenGenerator = new PasswordResetTokenGenerator();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new RequestPasswordResetCommandHandler(
            $this->mailer,
            $this->userCommandRepository,
            $this->passwordResetTokenGenerator,
            $this->logger
        );
    }

    /**
     * @throws RequestPasswordResetCommandHandlerException
     */
    public function testRequestPasswordResetSuccess(): void
    {
        $user = new User();
        $command = new RequestPasswordResetCommand($user);

        $this->userCommandRepository->expects($this->once())->method('save')->with($user);
        $this->mailer->expects($this->once())->method('sendPasswordResetEmail');

        $this->handler->__invoke($command);

        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getPasswordResetTokenExpiry());
    }

    /**
     * @throws RequestPasswordResetCommandHandlerException
     */
    public function testRequestPasswordResetExceptionDuringProcess(): void
    {
        $this->expectException(RequestPasswordResetCommandHandlerException::class);

        $user = new User();
        $command = new RequestPasswordResetCommand($user);

        $this->userCommandRepository->method('save')->willThrowException(new \Exception('Save error'));

        $this->handler->__invoke($command);
    }
}
