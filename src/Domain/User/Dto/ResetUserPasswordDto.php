<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

readonly class ResetUserPasswordDto implements ResetUserPasswordDtoInterface
{
    public function __construct(
        private string $token,
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
