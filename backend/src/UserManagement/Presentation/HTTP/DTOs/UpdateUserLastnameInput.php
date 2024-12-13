<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

final readonly class UpdateUserLastnameInput
{
    public function __construct(
        public string $lastname,
    ) {
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }
}
