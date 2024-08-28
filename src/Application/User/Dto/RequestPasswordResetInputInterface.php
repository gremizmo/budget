<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

interface RequestPasswordResetInputInterface
{
    public function getEmail(): string;
}
