<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Aggregates;

use App\EnvelopeManagement\Domain\Events\EnvelopeCreatedEvent;
use App\EnvelopeManagement\Domain\Events\EnvelopeCreditedEvent;
use App\EnvelopeManagement\Domain\Events\EnvelopeDebitedEvent;
use App\EnvelopeManagement\Domain\Events\EnvelopeDeletedEvent;
use App\EnvelopeManagement\Domain\Events\EnvelopeNamedEvent;
use App\EnvelopeManagement\Domain\Exceptions\EnvelopeNameAlreadyExistsForUserException;
use App\EnvelopeManagement\Domain\Exceptions\InvalidEnvelopeOperationException;
use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeCreditMoney;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeCurrentBudget;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeDebitMoney;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeId;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeName;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeTargetBudget;
use App\EnvelopeManagement\Domain\ValueObjects\UserId;
use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class Envelope
{
    private EnvelopeId $envelopeId;
    private UserId $userId;
    private \DateTime $updatedAt;
    private \DateTimeImmutable $createdAt;
    private EnvelopeCurrentBudget $currentBudget;
    private EnvelopeTargetBudget $targetBudget;
    private EnvelopeName $name;

    private bool $isDeleted;

    private array $uncommittedEvents = [];

    private function __construct()
    {
        $this->currentBudget = EnvelopeCurrentBudget::create('0.00', '100.00');
        $this->targetBudget = EnvelopeTargetBudget::create('100.00');
        $this->name = EnvelopeName::create('init');
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTimeImmutable();
        $this->isDeleted = false;
    }

    public static function reconstituteFromEvents(array $events): self
    {
        $aggregate = new self();

        foreach ($events as $event) {
            $aggregate->applyEvent($event['type']::fromArray(json_decode($event['payload'], true)));
        }

        return $aggregate;
    }

    public static function create(
        string $envelopeId,
        string $userId,
        string $targetBudget,
        string $name,
        EnvelopeRepositoryInterface $envelopeRepository,
    ): self {
        if ($envelopeRepository->findOneBy(['user_uuid' => $userId, 'name' => $name, 'is_deleted' => false])) {
            throw new EnvelopeNameAlreadyExistsForUserException(EnvelopeNameAlreadyExistsForUserException::MESSAGE, 400);
        }

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

    public function rename(
        EnvelopeName $name,
        UserId $userId,
        EnvelopeId $envelopeId,
        EnvelopeRepositoryInterface $envelopeRepository,
    ): void {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $envelope = $envelopeRepository->findOneBy(
            [
                'user_uuid' => $userId->toString(),
                'name' => $name->toString(),
                'is_deleted' => false,
            ],
        );

        if ($envelope && $envelope->getUuid() !== $envelopeId->toString()) {
            throw new EnvelopeNameAlreadyExistsForUserException(EnvelopeNameAlreadyExistsForUserException::MESSAGE, 400);
        }

        $event = new EnvelopeNamedEvent(
            $this->envelopeId->toString(),
            $name->toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function credit(EnvelopeCreditMoney $envelopeCreditMoney, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $event = new EnvelopeCreditedEvent(
            $this->envelopeId->toString(),
            $envelopeCreditMoney->toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function debit(EnvelopeDebitMoney $envelopeDebitMoney, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $event = new EnvelopeDebitedEvent(
            $this->envelopeId->toString(),
            $envelopeDebitMoney->toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function delete(UserId $userId): void
    {
        $this->assertOwnership($userId);

        $event = new EnvelopeDeletedEvent(
            $this->envelopeId->toString(),
            true,
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function getUncommittedEvents(): array
    {
        return $this->uncommittedEvents;
    }

    public function clearUncommitedEvent(): void
    {
        $this->uncommittedEvents = [];
    }

    private function applyEvent(EventInterface $event): void
    {
        match (get_class($event)) {
            EnvelopeCreatedEvent::class => $this->applyCreatedEvent($event),
            EnvelopeNamedEvent::class => $this->applyNamedEvent($event),
            EnvelopeCreditedEvent::class => $this->applyCreditedEvent($event),
            EnvelopeDebitedEvent::class => $this->applyDebitedEvent($event),
            EnvelopeDeletedEvent::class => $this->applyDeletedEvent($event),
            default => throw new \RuntimeException(sprintf('Unsupported event type: %s', get_class($event))),
        };
    }

    private function applyCreatedEvent(EnvelopeCreatedEvent $event): void
    {
        $this->envelopeId = EnvelopeId::create($event->getAggregateId());
        $this->userId = UserId::create($event->getUserId());
        $this->name = EnvelopeName::create($event->getName());
        $this->targetBudget = EnvelopeTargetBudget::create($event->getTargetBudget());
        $this->currentBudget = EnvelopeCurrentBudget::create('0.00', $event->getTargetBudget());
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    private function applyNamedEvent(EnvelopeNamedEvent $event): void
    {
        $this->name = EnvelopeName::create($event->getName());
        $this->updatedAt = new \DateTime();
    }

    private function applyCreditedEvent(EnvelopeCreditedEvent $event): void
    {
        $newBudget = (floatval($this->currentBudget->toString()) + floatval($event->getCreditMoney()));

        $this->currentBudget = EnvelopeCurrentBudget::create((string) $newBudget, $this->targetBudget->toString());
        $this->updatedAt = new \DateTime();
    }

    private function applyDebitedEvent(EnvelopeDebitedEvent $event): void
    {
        $newBudget = (floatval($this->currentBudget->toString()) - floatval($event->getDebitMoney()));

        $this->currentBudget = EnvelopeCurrentBudget::create((string) $newBudget, $this->targetBudget->toString());
        $this->updatedAt = new \DateTime();
    }

    private function applyDeletedEvent(EnvelopeDeletedEvent $event): void
    {
        $this->isDeleted = $event->isDeleted();
        $this->updatedAt = new \DateTime();
    }

    private function assertOwnership(UserId $userId): void
    {
        if (!$this->userId->equals($userId)) {
            throw new \RuntimeException('User does not have permission to access this envelope.');
        }
    }

    private function assertNotDeleted(): void
    {
        if ($this->isDeleted) {
            throw InvalidEnvelopeOperationException::operationOnDeletedEnvelope();
        }
    }

    private function recordEvent(EventInterface $event): void
    {
        $this->uncommittedEvents[] = $event;
    }
}
