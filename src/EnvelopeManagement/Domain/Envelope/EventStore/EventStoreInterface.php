<?php

namespace App\EnvelopeManagement\Domain\Envelope\EventStore;

use App\EnvelopeManagement\Domain\Envelope\Event\EventInterface;

interface EventStoreInterface
{
    public function append(EventInterface $event): void;
    public function getEventsForAggregate(string $aggregateId): iterable;
}
