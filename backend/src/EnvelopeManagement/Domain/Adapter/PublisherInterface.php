<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Adapter;

interface PublisherInterface
{
    public function publishEvents(array $events): void;
}
