<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\DTOs;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserPasswordInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 8, minMessage: 'The old password must be at least {{ limit }} characters long.')]
        private string $oldPassword,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8, minMessage: 'The new password must be at least {{ limit }} characters long.')]
        #[Assert\PasswordStrength]
        private string $newPassword
    ) {
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
