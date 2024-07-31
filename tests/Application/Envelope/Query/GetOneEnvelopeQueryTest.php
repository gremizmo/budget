<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Query;

use App\Application\Envelope\Query\ShowEnvelopeQuery;
use PHPUnit\Framework\TestCase;

class GetOneEnvelopeQueryTest extends TestCase
{
    public function testConstructorAndGetter(): void
    {
        $envelopeId = 1;
        $query = new ShowEnvelopeQuery($envelopeId);

        $this->assertSame($envelopeId, $query->getEnvelopeId());
    }
}
