<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

final readonly class ResetUserPasswordInput implements ResetUserPasswordInputInterface
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
