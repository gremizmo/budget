<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Adapter;

interface AMQPStreamConnectionInterface
{
    public function publishEvents(array $events): void;
}
