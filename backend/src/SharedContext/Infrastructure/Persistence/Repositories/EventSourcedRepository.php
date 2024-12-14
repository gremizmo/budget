<?php

namespace App\SharedContext\Infrastructure\Persistence\Repositories;

use App\SharedContext\Domain\Ports\Inbound\EventSourcedRepositoryInterface;
use App\SharedContext\EventStore\EventStoreInterface;

final readonly class EventSourcedRepository implements EventSourcedRepositoryInterface
{
    public function __construct(private EventStoreInterface $eventStore)
    {
    }

    #[\Override]
    public function get(string $aggregateId): array
    {
        return $this->eventStore->load($aggregateId);
    }

    #[\Override]
    public function save(array $uncommittedEvents): void
    {
        $this->eventStore->save($uncommittedEvents);
    }
}
