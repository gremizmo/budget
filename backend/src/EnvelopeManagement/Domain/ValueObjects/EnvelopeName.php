<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\ValueObjects;

final readonly class EnvelopeName
{
    private function __construct(protected string $name)
    {
        $nameLength = strlen($this->name);

        if (0 === $nameLength || $nameLength > 255) {
            throw new \InvalidArgumentException('Envelope name must be between 1 and 255 characters.');
        }
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
