<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObject;

readonly class EnvelopeName
{
    private function __construct(protected string $name)
    {
    }

    public static function withName(string $name): self
    {
        return new self($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
