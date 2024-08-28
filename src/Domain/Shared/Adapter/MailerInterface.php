<?php

namespace App\Domain\Shared\Adapter;

use App\Domain\Shared\Model\UserInterface;

interface MailerInterface
{
    public function sendPasswordResetEmail(UserInterface $user, string $token): void;
}
