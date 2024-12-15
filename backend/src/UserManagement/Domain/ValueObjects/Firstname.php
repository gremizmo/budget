<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

use Assert\Assert;

final readonly class Firstname
{
    private function __construct(protected string $name)
    {
        Assert::that($name)
            ->notBlank('Firstname should not be blank.')
            ->minLength(2, 'The first name must be at least 2 characters long.')
            ->maxLength(255, 'The first name cannot be longer than 255 characters.')
        ;
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function toString(): string
    {
        return $this->name;
    }
}
