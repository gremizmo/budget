<?php

namespace App\UserManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class UserLastnameUpdatedEvent implements EventInterface
{
    private string $aggregateId;
    private string $lastname;
    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $lastname)
    {
        $this->aggregateId = $aggregateId;
        $this->lastname = $lastname;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function toArray(): array
    {
        return [
            'aggregateId' => $this->aggregateId,
            'lastname' => $this->lastname,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    public static function fromArray(array $data): self
    {
        $event = new self($data['aggregateId'], $data['lastname']);
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
