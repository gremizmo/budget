<?php

namespace App\EnvelopeManagement\Domain\Shared\Adapter;

interface UuidAdapterInterface
{
    public function generate(): string;
}
