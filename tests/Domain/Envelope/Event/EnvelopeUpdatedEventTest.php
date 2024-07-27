<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Event;

use App\Domain\Envelope\Event\EnvelopeUpdatedEvent;
use PHPUnit\Framework\TestCase;

class EnvelopeUpdatedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        $changes = [
            'title' => ['new' => 'New Title', 'old' => 'Old Title'],
            'updatedAt' => [
                'new' => ['date' => '2023-10-01 00:00:00.000000', 'timezone' => 'UTC', 'timezone_type' => 3],
                'old' => ['date' => '2023-09-01 00:00:00.000000', 'timezone' => 'UTC', 'timezone_type' => 3],
            ],
            'updatedBy' => ['new' => 'user2', 'old' => 'user1'],
            'targetBudget' => ['new' => '2000.00', 'old' => '1000.00'],
            'currentBudget' => ['new' => '1500.00', 'old' => '500.00'],
        ];

        $event = new EnvelopeUpdatedEvent(1, $changes);

        $this->assertInstanceOf(EnvelopeUpdatedEvent::class, $event);
    }

    public function testGetEnvelopeId(): void
    {
        $changes = [];
        $event = new EnvelopeUpdatedEvent(1, $changes);

        $this->assertSame(1, $event->getEnvelopeId());
    }

    public function testGetChanges(): void
    {
        $changes = [
            'title' => ['new' => 'New Title', 'old' => 'Old Title'],
        ];
        $event = new EnvelopeUpdatedEvent(1, $changes);

        $this->assertSame($changes, $event->getChanges());
    }

    public function testGetOccurredOn(): void
    {
        $changes = [];
        $event = new EnvelopeUpdatedEvent(1, $changes);

        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getOccurredOn());
    }
}
