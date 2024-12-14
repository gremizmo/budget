<?php

namespace App\UserManagement\Domain\Services;

use App\UserManagement\Domain\Ports\Inbound\PasswordResetTokenGeneratorInterface;

final readonly class PasswordResetTokenGenerator implements PasswordResetTokenGeneratorInterface
{
    #[\Override]
    public function generate(): string
    {
        return \bin2hex(\random_bytes(32));
    }
}
