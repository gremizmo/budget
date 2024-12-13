<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Commands;

use App\UserManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class UpdateUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private string $oldPassword,
        private string $newPassword,
        private string $uuid,
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

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
