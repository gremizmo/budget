<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Dto;

interface ChangeUserPasswordInputInterface
{
    public function getOldPassword(): string;

    public function getNewPassword(): string;
}
