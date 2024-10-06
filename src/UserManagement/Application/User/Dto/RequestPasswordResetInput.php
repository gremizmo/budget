<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Dto;

final readonly class RequestPasswordResetInput implements RequestPasswordResetInputInterface
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
