<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Entity;

use App\Domain\Envelope\Model\EnvelopesPaginated;
use App\Infra\Http\Rest\Envelope\Entity\Envelope;
use PHPUnit\Framework\TestCase;

class EnvelopesPaginatedTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $envelopes = [new Envelope(), new Envelope()];
        $totalItems = 2;

        $envelopesPaginated = new EnvelopesPaginated($envelopes, $totalItems);

        $this->assertSame($envelopes, $envelopesPaginated->getEnvelopes());
        $this->assertSame($totalItems, $envelopesPaginated->getTotalItems());
    }
}
