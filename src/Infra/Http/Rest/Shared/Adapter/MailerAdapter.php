<?php

namespace App\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Adapter\MailerInterface;
use App\Domain\User\Entity\UserInterface;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class MailerAdapter implements MailerInterface
{
    public function __construct(
        private SymfonyMailerInterface $mailer,
        private UrlGeneratorAdapter $urlGeneratorAdapter,
    ) {
    }

    public function sendPasswordResetEmail(UserInterface $user, string $token): void
    {
        $passwordResetUrl = $this->generatePasswordResetUrl($token);

        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($user->getEmail())
            ->subject('Password Reset Request')
            ->html(sprintf('Click <a href="%s">here</a> to reset your password.', $passwordResetUrl));

        $this->send($email);
    }

    private function generatePasswordResetUrl(string $token): string
    {
        // TODO: Change this to your frontend URL
        return $this->urlGeneratorAdapter->generate('app_user_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(RawMessage $message, ?Envelope $envelope = null): void
    {
        $this->mailer->send($message, $envelope);
    }
}
