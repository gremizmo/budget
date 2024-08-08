<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Entity;

use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use PHPUnit\Framework\TestCase;

class EnvelopeTest extends TestCase
{
    public function testGetId(): void
    {
        $envelope = new Envelope();
        $envelope->setId(1);

        $this->assertSame(1, $envelope->getId());
    }

    public function testSetId(): void
    {
        $envelope = new Envelope();
        $envelope->setId(1);

        $this->assertSame(1, $envelope->getId());
    }

    public function testGetParent(): void
    {
        $parent = new Envelope();
        $envelope = new Envelope();
        $envelope->setParent($parent);

        $this->assertSame($parent, $envelope->getParent());
    }

    public function testSetParent(): void
    {
        $parent = new Envelope();
        $envelope = new Envelope();
        $envelope->setParent($parent);

        $this->assertSame($parent, $envelope->getParent());
    }

    public function testGetCreatedAt(): void
    {
        $envelope = new Envelope();

        $this->assertInstanceOf(\DateTimeImmutable::class, $envelope->getCreatedAt());
    }

    public function testSetCreatedAt(): void
    {
        $createdAt = new \DateTimeImmutable();
        $envelope = new Envelope();
        $envelope->setCreatedAt($createdAt);

        $this->assertSame($createdAt, $envelope->getCreatedAt());
    }

    public function testGetUpdatedAt(): void
    {
        $envelope = new Envelope();

        $this->assertInstanceOf(\DateTime::class, $envelope->getUpdatedAt());
    }

    public function testSetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $envelope = new Envelope();
        $envelope->setUpdatedAt($updatedAt);

        $this->assertSame($updatedAt, $envelope->getUpdatedAt());
    }

    public function testGetCurrentBudget(): void
    {
        $envelope = new Envelope();
        $envelope->setCurrentBudget('1000.00');

        $this->assertSame('1000.00', $envelope->getCurrentBudget());
    }

    public function testSetCurrentBudget(): void
    {
        $envelope = new Envelope();
        $envelope->setCurrentBudget('1000.00');

        $this->assertSame('1000.00', $envelope->getCurrentBudget());
    }

    public function testGetTargetBudget(): void
    {
        $envelope = new Envelope();
        $envelope->setTargetBudget('2000.00');

        $this->assertSame('2000.00', $envelope->getTargetBudget());
    }

    public function testSetTargetBudget(): void
    {
        $envelope = new Envelope();
        $envelope->setTargetBudget('2000.00');

        $this->assertSame('2000.00', $envelope->getTargetBudget());
    }

    public function testGetTitle(): void
    {
        $envelope = new Envelope();
        $envelope->setTitle('Test Title');

        $this->assertSame('Test Title', $envelope->getTitle());
    }

    public function testSetTitle(): void
    {
        $envelope = new Envelope();
        $envelope->setTitle('Test Title');

        $this->assertSame('Test Title', $envelope->getTitle());
    }

    public function testGetChildren(): void
    {
        $envelope = new Envelope();
        $envelope->setChildren(new EnvelopeCollection());

        $this->assertInstanceOf(EnvelopeCollection::class, $envelope->getChildren());
    }

    public function testSetChildren(): void
    {
        $children = new EnvelopeCollection();
        $envelope = new Envelope();
        $envelope->setChildren($children);

        $this->assertSame($children, $envelope->getChildren());
    }
}
