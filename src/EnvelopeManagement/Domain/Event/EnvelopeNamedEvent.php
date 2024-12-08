<?php

namespace App\EnvelopeManagement\Domain\Event;

class EnvelopeNamedEvent implements EventInterface
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

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function toArray(): array
    {
        return [
            'aggregateId' => $this->aggregateId,
            'name' => $this->name,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    public static function fromArray(array $data): self
    {
        $event = new self($data['aggregateId'], $data['name']);
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
