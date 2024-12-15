<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ResetUserPasswordInput
{
    public function __construct(
        #[Assert\NotBlank]
        private string $token,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8, minMessage: 'The new password must be at least {{ limit }} characters long.')]
        #[Assert\PasswordStrength]
        private string $newPassword
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
