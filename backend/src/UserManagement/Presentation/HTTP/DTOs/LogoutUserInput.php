<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

final readonly class LogoutUserInput
{
    public function __construct(
        public string $refreshToken,
    ) {
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
