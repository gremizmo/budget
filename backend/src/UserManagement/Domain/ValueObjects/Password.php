<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

use Assert\Assert;

final readonly class Password
{
    private function __construct(protected string $password)
    {
        Assert::that($password)
            ->notBlank('Password should not be blank.')
            ->minLength(8, 'The password must be at least 8 characters long.')
        ;
    }

    public static function create(string $password): self
    {
        return new self($password);
    }

    public function toString(): string
    {
        return $this->password;
    }
}
