<?php

namespace App\EnvelopeManagement\Domain\Adapter;

interface UuidAdapterInterface
{
    public function generate(): string;
}
