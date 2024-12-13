<?php

namespace App\UserManagement\Domain\Ports\Inbound;

interface PasswordResetTokenGeneratorInterface
{
    public function generate(): string;
}
