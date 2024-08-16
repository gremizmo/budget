<?php

namespace App\Domain\Shared\Adapter;

use App\Domain\User\Entity\UserInterface;

interface MailerInterface
{
    public function sendPasswordResetEmail(UserInterface $user, string $token): void;
}
