<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Dto;

interface ResetUserPasswordInputInterface
{
    public function getToken(): string;

    public function getNewPassword(): string;
}
