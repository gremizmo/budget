<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Dto;

final readonly class CreateUserInput implements CreateUserInputInterface
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstname,
        public string $lastname,
        public bool $consentGiven,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function isConsentGiven(): bool
    {
        return $this->consentGiven;
    }
}
