<?php

namespace App\EnvelopeManagement\Infrastructure\Envelope\Adapter;

use App\EnvelopeManagement\Domain\Envelope\Adapter\UuidAdapterInterface;
use Symfony\Component\Uid\Uuid;

readonly class UuidAdapter implements UuidAdapterInterface
{
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
