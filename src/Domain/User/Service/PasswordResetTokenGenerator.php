<?php

namespace App\Domain\User\Service;

class PasswordResetTokenGenerator implements PasswordResetTokenGeneratorInterface
{
    public function generate(): string
    {
        return \bin2hex(\random_bytes(32));
    }
}
