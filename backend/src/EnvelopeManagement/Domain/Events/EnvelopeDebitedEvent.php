<?php

namespace App\EnvelopeManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class EnvelopeDebitedEvent implements EventInterface
{
    private string $aggregateId;
    private string $debitMoney;
    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $debitMoney)
    {
        $this->aggregateId = $aggregateId;
        $this->debitMoney = $debitMoney;
        $this->occurredOn = new \DateTimeImmutable();
    }

    #[\Override]
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getDebitMoney(): string
    {
        return $this->debitMoney;
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
            'debitMoney' => $this->debitMoney,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    #[\Override]
    public static function fromArray(array $data): self
    {
        $event = new self($data['aggregateId'], $data['debitMoney']);
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
