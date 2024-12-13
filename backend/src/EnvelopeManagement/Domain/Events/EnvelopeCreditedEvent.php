<?php

namespace App\EnvelopeManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class EnvelopeCreditedEvent implements EventInterface
{
    private string $aggregateId;
    private string $creditMoney;
    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $creditMoney)
    {
        $this->aggregateId = $aggregateId;
        $this->creditMoney = $creditMoney;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getCreditMoney(): string
    {
        return $this->creditMoney;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function toArray(): array
    {
        return [
            'aggregateId' => $this->aggregateId,
            'creditMoney' => $this->creditMoney,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    public static function fromArray(array $data): self
    {
        $event = new self($data['aggregateId'], $data['creditMoney']);
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}
