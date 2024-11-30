<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Event;

interface EventInterface
{
    public function getAggregateId(): string;

    public function getPayload(): array;

    public function getOccurredOn(): \DateTimeImmutable;
}
