<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Factory;

use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Factory\EnvelopeCollectionFactory;
use PHPUnit\Framework\TestCase;

class EnvelopeCollectionFactoryTest extends TestCase
{
    private EnvelopeCollectionFactory $envelopeCollectionFactory;

    protected function setUp(): void
    {
        $this->envelopeCollectionFactory = new EnvelopeCollectionFactory();
    }

    public function testCreate(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = [$envelope1, $envelope2];

        $envelopeCollection = $this->envelopeCollectionFactory->create($collection);

        $this->assertInstanceOf(EnvelopeCollection::class, $envelopeCollection);
        $this->assertSame($collection, $envelopeCollection->toArray());
    }
}
