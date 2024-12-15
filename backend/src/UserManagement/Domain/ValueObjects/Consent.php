<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\ValueObjects;

use Assert\Assert;

final readonly class Consent
{
    private function __construct(protected bool $consent)
    {
        Assert::that($consent)
            ->notNull('Consent must be provided.')
            ->boolean('The consent must be a boolean value.')
            ->true('Consent must be accepted.')
        ;
    }

    public static function create(bool $consent): self
    {
        return new self($consent);
    }
}
