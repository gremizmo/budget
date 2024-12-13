<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

use Assert\Assertion;

final readonly class Password
{
    private function __construct(protected string $password)
    {
        Assertion::notBlank($this->password);
        Assertion::string($this->password);
    }

    public static function create(string $password): self
    {
        return new self($password);
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
