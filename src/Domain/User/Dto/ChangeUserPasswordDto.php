<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

final readonly class ChangeUserPasswordDto implements ChangeUserPasswordDtoInterface
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
