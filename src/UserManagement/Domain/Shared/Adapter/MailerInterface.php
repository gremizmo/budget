<?php

namespace App\UserManagement\Domain\Shared\Adapter;

use App\UserManagement\Domain\User\Model\UserInterface;

interface MailerInterface
{
    public function sendPasswordResetEmail(UserInterface $user, string $token): void;
}
