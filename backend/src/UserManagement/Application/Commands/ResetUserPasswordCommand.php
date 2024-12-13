<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Commands;

use App\UserManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class ResetUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private string $resetToken,
        private string $newPassword,
    ) {
    }

    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
