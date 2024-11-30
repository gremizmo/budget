<?php

namespace App\EnvelopeManagement\Application\Envelope\Event;

use App\EnvelopeManagement\Domain\Envelope\Event\EventInterface;

class EnvelopeCreatedEvent implements EventInterface
{
    private string $aggregateId;
    private array $payload;
    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, array $payload)
    {
        $this->aggregateId = $aggregateId;
        $this->payload = $payload;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
