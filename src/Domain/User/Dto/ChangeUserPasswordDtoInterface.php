<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

interface ChangeUserPasswordDtoInterface
{
    public function getOldPassword(): string;

    public function getNewPassword(): string;
}
