<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

final readonly class PasswordResetToken
{
    private function __construct(protected string $passwordResetToken)
    {
    }

    public static function create(string $passwordResetToken): self
    {
        return new self($passwordResetToken);
    }

    public function toString(): string
    {
        return $this->passwordResetToken;
    }
}
