<?php

namespace App\UserManagement\Domain\Adapter;

use App\UserManagement\Domain\Model\UserInterface;

interface MailerInterface
{
    public function sendPasswordResetEmail(UserInterface $user, string $token): void;
}
