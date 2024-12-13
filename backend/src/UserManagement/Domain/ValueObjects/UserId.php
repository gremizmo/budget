<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

final readonly class UserId
{
    /**
     * @throws AssertionFailedException
     */
    private function __construct(protected string $uuid)
    {
        Assertion::uuid($uuid);
    }

    /**
     * @throws AssertionFailedException
     */
    public static function create(string $uuid): self
    {
        return new self($uuid);
    }

    public function equals(UserId $userId): bool
    {
        return $userId->__toString() === $this->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
