<?php

declare(strict_types=1);

namespace App\Domain\Shared\Adapter;

interface UuidGeneratorInterface
{
    public function generateUuid(): string;
}
