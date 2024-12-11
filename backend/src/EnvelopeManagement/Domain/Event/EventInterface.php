<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Event;

interface EventInterface
{
    public function getAggregateId(): string;

    public function occurredOn(): \DateTimeImmutable;

    public function toArray(): array;

    public static function fromArray(array $data): self;
}
