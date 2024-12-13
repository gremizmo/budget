<?php

namespace App\UserManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class UserPasswordUpdatedEvent implements EventInterface
{
    private string $aggregateId;
    private string $oldPassword;

    private string $newPassword;

    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $oldPassword, string $newPassword)
    {
        $this->aggregateId = $aggregateId;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function toArray(): array
    {
        return [
            'aggregateId' => $this->aggregateId,
            'oldPassword' => $this->oldPassword,
            'newPassword' => $this->newPassword,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    public static function fromArray(array $data): self
    {
        $event = new self($data['aggregateId'], $data['oldPassword'], $data['newPassword']);
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
