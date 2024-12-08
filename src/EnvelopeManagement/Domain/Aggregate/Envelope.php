<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Aggregate;

use App\EnvelopeManagement\Domain\Event\EnvelopeCreatedEvent;
use App\EnvelopeManagement\Domain\Event\EnvelopeCreditedEvent;
use App\EnvelopeManagement\Domain\Event\EnvelopeDebitedEvent;
use App\EnvelopeManagement\Domain\Event\EnvelopeDeletedEvent;
use App\EnvelopeManagement\Domain\Event\EnvelopeNamedEvent;
use App\EnvelopeManagement\Domain\Event\EventInterface;
use App\EnvelopeManagement\Domain\Exception\EnvelopeNameAlreadyExistsForUserException;
use App\EnvelopeManagement\Domain\Exception\InvalidEnvelopeOperationException;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeCreditMoney;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeCurrentBudget;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeDebitMoney;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeId;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeName;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeTargetBudget;
use App\EnvelopeManagement\Domain\ValueObject\UserId;

class Envelope implements EnvelopeInterface
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
        EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ): self {
        if ($envelopeQueryRepository->findOneBy(['user_uuid' => $userId, 'name' => $name, 'is_deleted' => false])) {
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

    public function rename(EnvelopeName $name, UserId $userId, EnvelopeQueryRepositoryInterface $envelopeQueryRepository): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        if ($envelopeQueryRepository->findOneBy(
            [
                'user_uuid' => $userId->__toString(),
                'name' => $name->__toString(),
                'is_deleted' => false,
            ],
        )) {
            throw new EnvelopeNameAlreadyExistsForUserException(EnvelopeNameAlreadyExistsForUserException::MESSAGE, 400);
        }

        $event = new EnvelopeNamedEvent(
            $this->envelopeId->__toString(),
            $name->__toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function credit(EnvelopeCreditMoney $envelopeCreditMoney, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $event = new EnvelopeCreditedEvent(
            $this->envelopeId->__toString(),
            $envelopeCreditMoney->__toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function debit(EnvelopeDebitMoney $envelopeDebitMoney, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $event = new EnvelopeDebitedEvent(
            $this->envelopeId->__toString(),
            $envelopeDebitMoney->__toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function delete(UserId $userId): void
    {
        $this->assertOwnership($userId);

        $event = new EnvelopeDeletedEvent(
            $this->envelopeId->__toString(),
            true,
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
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
        $newBudget = (floatval($this->currentBudget->__toString()) - floatval($event->getCreditMoney()));

        $this->currentBudget = EnvelopeCurrentBudget::create((string) $newBudget, $this->targetBudget->__toString());
        $this->updatedAt = new \DateTime();
    }

    private function applyDebitedEvent(EnvelopeDebitedEvent $event): void
    {
        $newBudget = (floatval($this->currentBudget->__toString()) + floatval($event->getDebitMoney()));

        $this->currentBudget = EnvelopeCurrentBudget::create((string) $newBudget, $this->targetBudget->__toString());
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

    public function getUncommittedEvents(): array
    {
        return $this->uncommittedEvents;
    }
}
