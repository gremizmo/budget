<?php

namespace App\UserManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class UserPasswordResetEvent implements EventInterface
{
    private string $aggregateId;
    private string $password;
    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $password)
    {
        $this->aggregateId = $aggregateId;
        $this->password = $password;
        $this->occurredOn = new \DateTimeImmutable();
    }

    #[\Override]
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getPassword(): string
    {
        return $this->password;
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
            'password' => $this->password,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    #[\Override]
    public static function fromArray(array $data): self
    {
        $event = new self($data['aggregateId'], $data['password']);
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
