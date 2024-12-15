<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[Assert\Regex(
            pattern: '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
        )]
        public string $uuid,

        #[Assert\NotBlank]
        #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(
            min: 8,
            minMessage: 'The password must be at least {{ limit }} characters long.'
        )]
        #[Assert\PasswordStrength]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'The first name must be at least {{ limit }} characters long.',
            maxMessage: 'The first name cannot be longer than {{ limit }} characters.'
        )]
        public string $firstname,

        #[Assert\NotBlank]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'The last name must be at least {{ limit }} characters long.',
            maxMessage: 'The last name cannot be longer than {{ limit }} characters.'
        )]
        public string $lastname,

        #[Assert\NotNull]
        #[Assert\IsTrue(message: 'Consent must be accepted.')]
        #[Assert\Type(type: 'bool', message: 'The consent must be a boolean value.')]
        public bool $consentGiven,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
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
