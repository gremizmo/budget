<?php

namespace App\UserManagement\Domain\User\Service;

interface PasswordResetTokenGeneratorInterface
{
    public function generate(): string;
}
