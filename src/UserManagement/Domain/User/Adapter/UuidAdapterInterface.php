<?php

namespace App\UserManagement\Domain\User\Adapter;

interface UuidAdapterInterface
{
    public function generate(): string;
}
