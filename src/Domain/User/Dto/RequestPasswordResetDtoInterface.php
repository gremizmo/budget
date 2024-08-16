<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

interface RequestPasswordResetDtoInterface
{
    public function getEmail(): string;
}
