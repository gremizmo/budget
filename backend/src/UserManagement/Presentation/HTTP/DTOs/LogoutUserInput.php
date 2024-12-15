<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class LogoutUserInput
{
    public function __construct(
        #[Assert\NotBlank]
        public string $refreshToken,
    ) {
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
