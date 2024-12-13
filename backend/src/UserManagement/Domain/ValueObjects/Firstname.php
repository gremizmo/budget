<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

final readonly class Firstname
{
    private function __construct(protected string $name)
    {
        $nameLength = strlen($this->name);

        if (0 === $nameLength || $nameLength > 50) {
            throw new \InvalidArgumentException('Envelope name must be between 1 and 50 characters.');
        }
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
