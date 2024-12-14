<?php

namespace App\UserManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class UserPasswordResetRequestedEvent implements EventInterface
{
    private string $aggregateId;
    private string $passwordResetToken;
    private \DateTimeImmutable $passwordResetTokenExpiry;
    private \DateTimeImmutable $occurredOn;

    public function __construct(
        string $aggregateId,
        string $passwordResetToken,
        \DateTimeImmutable $passwordResetTokenExpiry,
    ) {
        $this->aggregateId = $aggregateId;
        $this->passwordResetToken = $passwordResetToken;
        $this->passwordResetTokenExpiry = $passwordResetTokenExpiry;
        $this->occurredOn = new \DateTimeImmutable();
    }

    #[\Override]
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getPasswordResetToken(): string
    {
        return $this->passwordResetToken;
    }

    public function getPasswordResetTokenExpiry(): \DateTimeImmutable
    {
        return $this->passwordResetTokenExpiry;
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
            'passwordResetToken' => $this->passwordResetToken,
            'passwordResetTokenExpiry' => $this->passwordResetTokenExpiry->format(\DateTimeInterface::ATOM),
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    #[\Override]
    public static function fromArray(array $data): self
    {
        $event = new self(
            $data['aggregateId'],
            $data['passwordResetToken'],
            new \DateTimeImmutable(
                $data['passwordResetTokenExpiry']
            ),
        );
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
