<?php

namespace App\UserManagement\Infrastructure\Adapter;

use App\UserManagement\Domain\Adapter\UuidAdapterInterface;
use Symfony\Component\Uid\Uuid;

readonly class UuidAdapter implements UuidAdapterInterface
{
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}