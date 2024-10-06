<?php

namespace App\EnvelopeManagement\Domain\Envelope\Adapter;

interface UuidAdapterInterface
{
    public function generate(): string;
}
