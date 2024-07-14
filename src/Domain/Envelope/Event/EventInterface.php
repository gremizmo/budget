<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Event;

interface EventInterface
{
    public function getOccurredOn(): \DateTimeImmutable;
}
