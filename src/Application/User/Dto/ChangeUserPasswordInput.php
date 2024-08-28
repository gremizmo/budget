<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

final readonly class ChangeUserPasswordInput implements ChangeUserPasswordInputInterface
{
    public function __construct(
        private string $oldPassword,
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
