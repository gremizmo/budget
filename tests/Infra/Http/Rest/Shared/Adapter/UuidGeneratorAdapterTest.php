<?php

declare(strict_types=1);

namespace App\Tests\Infra\Http\Rest\Shared\Adapter;

use App\Infra\Http\Rest\Shared\Adapter\UuidGeneratorAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UuidGeneratorAdapterTest extends TestCase
{
    public function testGenerateUuid(): void
    {
        $adapter = new UuidGeneratorAdapter();
        $uuid = $adapter->generateUuid();

        $this->assertTrue(Uuid::isValid($uuid));
    }
}
