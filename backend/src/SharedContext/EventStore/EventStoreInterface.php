<?php

declare(strict_types=1);

namespace App\SharedContext\EventStore;

interface EventStoreInterface
{
    public function load(string $uuid): array;

    public function save(array $events): void;
}
