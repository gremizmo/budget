<?php

namespace App\UserManagement\Domain\Shared\Adapter;

interface UuidAdapterInterface
{
    public function generate(): string;
}
