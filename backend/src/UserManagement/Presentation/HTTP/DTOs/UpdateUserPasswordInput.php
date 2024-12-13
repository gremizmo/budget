<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

final readonly class UpdateUserPasswordInput
{
    public function __construct(
        private string $oldPassword,
        private string $newPassword
    ) {
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
