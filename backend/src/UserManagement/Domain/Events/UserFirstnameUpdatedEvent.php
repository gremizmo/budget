<?php

namespace App\UserManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class UserFirstnameUpdatedEvent implements EventInterface
{
    private string $aggregateId;
    private string $firstname;
    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $firstname)
    {
        $this->aggregateId = $aggregateId;
        $this->firstname = $firstname;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function toArray(): array
    {
        return [
            'aggregateId' => $this->aggregateId,
            'firstname' => $this->firstname,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    public static function fromArray(array $data): self
    {
        $event = new self($data['aggregateId'], $data['firstname']);
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
