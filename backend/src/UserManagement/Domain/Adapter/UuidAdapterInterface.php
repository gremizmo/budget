<?php

namespace App\UserManagement\Domain\Adapter;

interface UuidAdapterInterface
{
    public function generate(): string;
}
