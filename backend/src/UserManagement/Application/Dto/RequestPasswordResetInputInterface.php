<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Dto;

interface RequestPasswordResetInputInterface
{
    public function getEmail(): string;
}
