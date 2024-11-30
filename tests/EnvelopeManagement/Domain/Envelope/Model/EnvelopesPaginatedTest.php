<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Domain\Envelope\Model;

use App\EnvelopeManagement\Domain\Envelope\View\EnvelopesPaginated;
use PHPUnit\Framework\TestCase;

class EnvelopesPaginatedTest extends TestCase
{
    public function testGetEnvelopes(): void
    {
        $envelopes = [$this->createMock(\stdClass::class)];
        $envelopesPaginated = new EnvelopesPaginated($envelopes, 1);
        $this->assertEquals($envelopes, $envelopesPaginated->getEnvelopes());
    }

    public function testGetTotalItems(): void
    {
        $envelopes = [$this->createMock(\stdClass::class)];
        $envelopesPaginated = new EnvelopesPaginated($envelopes, 1);
        $this->assertEquals(1, $envelopesPaginated->getTotalItems());
    }
}
