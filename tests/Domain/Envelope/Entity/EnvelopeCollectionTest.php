<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Entity;

use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use PHPUnit\Framework\TestCase;

class EnvelopeCollectionTest extends TestCase
{
    public function testToArray(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);

        $this->assertSame([$envelope1, $envelope2], $collection->toArray());
    }

    public function testMap(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);
        $mappedCollection = $collection->map(fn ($envelope) => $envelope);

        $this->assertInstanceOf(EnvelopeCollection::class, $mappedCollection);
        $this->assertSame([$envelope1, $envelope2], $mappedCollection->toArray());
    }

    public function testFilter(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);
        $filteredCollection = $collection->filter(fn ($envelope) => $envelope === $envelope1);

        $this->assertInstanceOf(EnvelopeCollection::class, $filteredCollection);
        $this->assertSame([$envelope1], $filteredCollection->toArray());
    }

    public function testReduce(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);
        $reducedValue = $collection->reduce(fn ($carry, $envelope) => $carry + 1, 0);

        $this->assertSame(2, $reducedValue);
    }

    public function testGetIterator(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);
        $iterator = $collection->getIterator();

        $this->assertInstanceOf(\Traversable::class, $iterator);
        $this->assertSame([$envelope1, $envelope2], iterator_to_array($iterator));
    }

    public function testCount(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);

        $this->assertSame(2, $collection->count());
    }

    public function testContains(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1]);

        $this->assertTrue($collection->contains($envelope1));
        $this->assertFalse($collection->contains($envelope2));
    }

    public function testCurrent(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);

        $this->assertSame($envelope1, $collection->current());
    }

    public function testNext(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);
        $collection->next();

        $this->assertSame($envelope2, $collection->current());
    }

    public function testKey(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1, $envelope2]);

        $this->assertSame(0, $collection->key());
        $collection->next();
        $this->assertSame(1, $collection->key());
    }

    public function testAdd(): void
    {
        $envelope1 = new Envelope();
        $envelope2 = new Envelope();
        $collection = new EnvelopeCollection([$envelope1]);
        $collection->add($envelope2);

        $this->assertSame([$envelope1, $envelope2], $collection->toArray());
    }
}
