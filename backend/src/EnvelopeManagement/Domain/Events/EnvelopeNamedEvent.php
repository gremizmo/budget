<?php

namespace App\EnvelopeManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class EnvelopeNamedEvent implements EventInterface
{
    private string $aggregateId;
    private string $name;
    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $name)
    {
        $this->aggregateId = $aggregateId;
        $this->name = $name;
        $this->occurredOn = new \DateTimeImmutable();
    }

    #[\Override]
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    #[\Override]
    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'aggregateId' => $this->aggregateId,
            'name' => $this->name,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    #[\Override]
    public static function fromArray(array $data): self
    {
        $event = new self($data['aggregateId'], $data['name']);
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
