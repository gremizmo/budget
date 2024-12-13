<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

final readonly class RequestUserPasswordResetInput
{
    public function __construct(
        private string $email
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
