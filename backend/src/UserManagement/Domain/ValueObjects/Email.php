<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

use Assert\Assertion;

final readonly class Email
{
    private function __construct(protected string $email)
    {
        Assertion::email($this->email);
    }

    public static function create(string $email): self
    {
        return new self($email);
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->email;
    }
}
