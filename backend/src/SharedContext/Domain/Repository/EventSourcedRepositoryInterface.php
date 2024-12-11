<?php

namespace App\SharedContext\Domain\Repository;

interface EventSourcedRepositoryInterface
{
    public function get(string $aggregateId): array;

    public function save(array $uncommittedEvents): void;
}
