<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\EventStore;

use App\Domain\Envelope\Entity\EnvelopeHistory;
use App\Domain\Envelope\Event\EventInterface;
use Doctrine\ORM\EntityManagerInterface;

class EventStore
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function append(EventInterface $event): void
    {
        $updatedBy = 'system';

        $eventEntity = new EnvelopeHistory(
            $event->getEnvelopeId(),
            $updatedBy,
            $event->getChanges()
        );
        $this->entityManager->persist($eventEntity);
        $this->entityManager->flush();
    }
}
