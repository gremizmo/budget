<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Commands;

use App\UserManagement\Domain\Ports\Inbound\CommandInterface;

final readonly class LogoutUserCommand implements CommandInterface
{
    public function __construct(
        private string $refreshToken,
    ) {
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
