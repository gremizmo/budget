<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Dto;

final readonly class EditUserInput implements EditUserInputInterface
{
    public function __construct(
        public string $firstname,
        public string $lastname,
    ) {
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }
}
