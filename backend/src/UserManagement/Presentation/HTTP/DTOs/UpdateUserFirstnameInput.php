<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

final readonly class UpdateUserFirstnameInput
{
    public function __construct(
        public string $firstname,
    ) {
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }
}
