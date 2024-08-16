<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

readonly class RequestPasswordResetDto implements RequestPasswordResetDtoInterface
{
    public function __construct(
        private string $email
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
