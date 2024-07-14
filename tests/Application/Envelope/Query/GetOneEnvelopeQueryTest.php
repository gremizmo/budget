<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Query;

use App\Application\Envelope\Query\GetOneEnvelopeQuery;
use PHPUnit\Framework\TestCase;

class GetOneEnvelopeQueryTest extends TestCase
{
    public function testConstructorAndGetter(): void
    {
        $envelopeId = 1;
        $query = new GetOneEnvelopeQuery($envelopeId);

        $this->assertSame($envelopeId, $query->getEnvelopeId());
    }
}
