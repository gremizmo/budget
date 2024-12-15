<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

use Assert\Assert;

final readonly class EnvelopeName
{
    private function __construct(protected string $name)
    {
        Assert::that($name)
            ->notBlank('Name should not be blank.')
            ->minLength(1, 'The name must be at least 1 character long.')
            ->maxLength(50, 'The name must be at most 50 characters long.')
            ->regex('/^[\p{L}\p{N} ]+$/u', 'The name can only contain letters (including letters with accents), numbers (0-9), and spaces. No special characters are allowed.')
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
