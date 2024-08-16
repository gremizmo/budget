<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

interface ResetUserPasswordDtoInterface
{
    public function getToken(): string;

    public function getNewPassword(): string;
}
