<?php

namespace App\SharedContext\Domain\Ports\Inbound;

interface EventSourcedRepositoryInterface
{
    public function get(string $aggregateId): array;

    public function save(array $uncommittedEvents): void;
}
