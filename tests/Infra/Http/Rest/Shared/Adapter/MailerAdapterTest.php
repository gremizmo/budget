<?php

declare(strict_types=1);

namespace App\Tests\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Model\UserInterface;
use App\Infra\Http\Rest\Shared\Adapter\MailerAdapter;
use App\Infra\Http\Rest\Shared\Adapter\UrlGeneratorAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyUrlGeneratorInterface;

class MailerAdapterTest extends TestCase
{
    private SymfonyMailerInterface&MockObject $mailer;
    private SymfonyUrlGeneratorInterface&MockObject $symfonyUrlGenerator;
    private UserInterface&MockObject $user;
    private MailerAdapter $mailerAdapter;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(SymfonyMailerInterface::class);
        $this->symfonyUrlGenerator = $this->createMock(SymfonyUrlGeneratorInterface::class);
        $this->user = $this->createMock(UserInterface::class);
        $urlGeneratorAdapter = new UrlGeneratorAdapter($this->symfonyUrlGenerator);
        $this->mailerAdapter = new MailerAdapter($this->mailer, $urlGeneratorAdapter);
    }

    public function testSendPasswordResetEmail(): void
    {
        $token = 'reset-token';
        $email = 'user@example.com';
        $passwordResetUrl = 'http://example.com/reset-password?token=reset-token';

        $this->user->method('getEmail')->willReturn($email);
        $this->symfonyUrlGenerator->method('generate')->with('app_user_reset_password', ['token' => $token], SymfonyUrlGeneratorInterface::ABSOLUTE_URL)->willReturn($passwordResetUrl);

        $this->mailer->expects($this->once())->method('send')->with($this->callback(function (Email $email) use ($passwordResetUrl) {
            return 'user@example.com' === $email->getTo()[0]->getAddress()
                   && 'Password Reset Request' === $email->getSubject()
                   && \str_contains((string) $email->getHtmlBody(), $passwordResetUrl);
        }));

        $this->mailerAdapter->sendPasswordResetEmail($this->user, $token);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testSend(): void
    {
        $message = $this->createMock(RawMessage::class);
        $envelope = $this->createMock(Envelope::class);

        $this->mailer->expects($this->once())->method('send')->with($message, $envelope);

        $this->mailerAdapter->send($message, $envelope);
    }
}
