<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RequestUserPasswordResetInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
        private string $email
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
