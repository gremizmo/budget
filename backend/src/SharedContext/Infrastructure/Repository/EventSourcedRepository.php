<?php

namespace App\SharedContext\Infrastructure\Repository;

use App\SharedContext\Domain\Repository\EventSourcedRepositoryInterface;
use App\SharedContext\Lib\EventStoreInterface;

final readonly class EventSourcedRepository implements EventSourcedRepositoryInterface
{
    public function __construct(private EventStoreInterface $eventStore)
    {
    }

    public function get(string $aggregateId): array
    {
        return $this->eventStore->load($aggregateId);
    }

    public function save(array $uncommittedEvents): void
    {
        $this->eventStore->save($uncommittedEvents);
    }
}
