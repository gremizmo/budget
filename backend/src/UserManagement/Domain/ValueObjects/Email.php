<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

use Assert\Assert;

final readonly class Email
{
    private function __construct(protected string $email)
    {
        Assert::that($email)
            ->notBlank('Email should not be blank.')
            ->email('The email "{{ value }}" is not a valid email.')
        ;
    }

    public static function create(string $email): self
    {
        return new self($email);
    }

    public function toString(): string
    {
        return $this->email;
    }
}
