<?php

namespace App\UserManagement\Domain\Ports\Outbound;

use App\UserManagement\Domain\Ports\Inbound\UserViewInterface;

interface MailerInterface
{
    public function sendPasswordResetEmail(UserViewInterface $user, string $token): void;
}
