<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Query;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryTest extends TestCase
{
    public function testConstructorAndGetterWithNull(): void
    {
        $query = new ListEnvelopesQuery();

        $this->assertNull($query->getEnvelopeId());
    }

    public function testConstructorAndGetterWithNonNull(): void
    {
        $envelopeId = 1;
        $query = new ListEnvelopesQuery($envelopeId);

        $this->assertSame($envelopeId, $query->getEnvelopeId());
    }
}
