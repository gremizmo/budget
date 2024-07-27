<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Shared\Adapter;

use App\Domain\Shared\Adapter\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class UuidGeneratorAdapter implements UuidGeneratorInterface
{
    public function generateUuid(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
