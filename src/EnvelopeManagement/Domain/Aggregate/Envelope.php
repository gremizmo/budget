<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Aggregate;

use App\EnvelopeManagement\Application\Event\EnvelopeCreatedEvent;
use App\EnvelopeManagement\Application\Event\EnvelopeNamedEvent;
use App\EnvelopeManagement\Domain\Event\EventInterface;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeCurrentBudget;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeId;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeName;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeTargetBudget;
use App\EnvelopeManagement\Domain\ValueObject\UserId;

class Envelope
{
    private EnvelopeId $envelopeId;
    private UserId $userId;
    private \DateTime $updatedAt;
    private \DateTimeImmutable $createdAt;
    private EnvelopeCurrentBudget $currentBudget;
    private EnvelopeTargetBudget $targetBudget;
    private EnvelopeName $name;

    private array $uncommittedEvents = [];

    private function __construct()
    {
        $this->currentBudget = EnvelopeCurrentBudget::withCurrentBudget('0.00');
        $this->targetBudget = EnvelopeTargetBudget::withTargetBudget('0.00');
        $this->name = EnvelopeName::withName('');
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function create(
        string $envelopeId,
        string $userId,
        string $targetBudget,
        string $name
    ): self {
        $aggregate = new self();

        $event = new EnvelopeCreatedEvent(
            $envelopeId,
            $userId,
            $name,
            $targetBudget,
        );

        $aggregate->applyEvent($event);
        $aggregate->recordEvent($event);

        return $aggregate;
    }

    public static function reconstituteFromEvents(array $events): self
    {
        $aggregate = new self();

        foreach ($events as $event) {
            $aggregate->applyEvent($event['type']::fromArray(json_decode($event['payload'], true)));
        }

        return $aggregate;
    }

    public function rename(EnvelopeName $name, UserId $userId): void
    {
        $this->assertOwnership($userId);

        $event = new EnvelopeNamedEvent(
            $this->envelopeId->__toString(),
            $name->__toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    private function applyEvent(EventInterface $event): void
    {
        match (get_class($event)) {
            EnvelopeCreatedEvent::class => $this->applyCreatedEvent($event),
            EnvelopeNamedEvent::class => $this->applyNamedEvent($event),
            default => throw new \RuntimeException(sprintf('Unsupported event type: %s', get_class($event))),
        };
    }

    private function applyCreatedEvent(EnvelopeCreatedEvent $event): void
    {
        $this->envelopeId = EnvelopeId::withUuid($event->getAggregateId());
        $this->userId = UserId::withUuid($event->getUserId());
        $this->name = EnvelopeName::withName($event->getName());
        $this->targetBudget = EnvelopeTargetBudget::withTargetBudget($event->getTargetBudget());
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    private function applyNamedEvent(EnvelopeNamedEvent $event): void
    {
        $this->name = EnvelopeName::withName($event->getName());
        $this->updatedAt = new \DateTime();
    }

    private function recordEvent(EventInterface $event): void
    {
        $this->uncommittedEvents[] = $event;
    }

    private function assertOwnership(UserId $userId): void
    {
        if (!$this->userId->equals($userId)) {
            throw new \RuntimeException('User does not have permission to access this envelope.');
        }
    }

    public function getUncommittedEvents(): array
    {
        return $this->uncommittedEvents;
    }

    public function getEnvelopeId(): EnvelopeId
    {
        return $this->envelopeId;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getCurrentBudget(): EnvelopeCurrentBudget
    {
        return $this->currentBudget;
    }

    public function getTargetBudget(): EnvelopeTargetBudget
    {
        return $this->targetBudget;
    }

    public function getName(): EnvelopeName
    {
        return $this->name;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
