<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

interface ResetUserPasswordInputInterface
{
    public function getToken(): string;

    public function getNewPassword(): string;
}
