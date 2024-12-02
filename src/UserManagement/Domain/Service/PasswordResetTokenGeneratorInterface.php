<?php

namespace App\UserManagement\Domain\Service;

interface PasswordResetTokenGeneratorInterface
{
    public function generate(): string;
}
