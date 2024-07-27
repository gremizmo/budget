<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Entity;

use App\Domain\Envelope\Entity\EnvelopeHistory;
use PHPUnit\Framework\TestCase;

class EnvelopeHistoryTest extends TestCase
{
    public function testGetId(): void
    {
        $history = new EnvelopeHistory(1, 'user1', []);
        $history->setId(1);

        $this->assertSame(1, $history->getId());
    }

    public function testSetId(): void
    {
        $history = new EnvelopeHistory(1, 'user1', []);
        $history->setId(1);

        $this->assertSame(1, $history->getId());
    }

    public function testGetEnvelopeId(): void
    {
        $envelopeId = 1;
        $history = new EnvelopeHistory($envelopeId, 'user1', []);

        $this->assertSame($envelopeId, $history->getEnvelopeId());
    }

    public function testGetUpdatedAt(): void
    {
        $history = new EnvelopeHistory(1, 'user1', []);

        $this->assertInstanceOf(\DateTimeImmutable::class, $history->getUpdatedAt());
    }

    public function testSetUpdatedAt(): void
    {
        $updatedAt = new \DateTimeImmutable();
        $history = new EnvelopeHistory(1, 'user1', []);
        $history->setUpdatedAt($updatedAt);

        $this->assertSame($updatedAt, $history->getUpdatedAt());
    }

    public function testGetUpdatedBy(): void
    {
        $updatedBy = 'user1';
        $history = new EnvelopeHistory(1, $updatedBy, []);

        $this->assertSame($updatedBy, $history->getUpdatedBy());
    }

    public function testSetUpdatedBy(): void
    {
        $updatedBy = 'user1';
        $history = new EnvelopeHistory(1, 'user2', []);
        $history->setUpdatedBy($updatedBy);

        $this->assertSame($updatedBy, $history->getUpdatedBy());
    }

    public function testGetChanges(): void
    {
        $changes = ['change1', 'change2'];
        $history = new EnvelopeHistory(1, 'user1', $changes);

        $this->assertSame($changes, $history->getChanges());
    }

    public function testSetChanges(): void
    {
        $changes = ['change1', 'change2'];
        $history = new EnvelopeHistory(1, 'user1', []);
        $history->setChanges($changes);

        $this->assertSame($changes, $history->getChanges());
    }
}
