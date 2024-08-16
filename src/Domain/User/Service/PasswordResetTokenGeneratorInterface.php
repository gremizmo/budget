<?php

namespace App\Domain\User\Service;

interface PasswordResetTokenGeneratorInterface
{
    public function generate(): string;
}
