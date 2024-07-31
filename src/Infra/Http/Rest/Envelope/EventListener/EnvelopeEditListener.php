<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\EventListener;

use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Event\EnvelopeEditedEvent;
use App\Infra\Http\Rest\Envelope\EventStore\EventStore;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class EnvelopeEditListener
{
    private static bool $processingHistory = false;

    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        if (self::$processingHistory) {
            return;
        }
        $entity = $args->getObject();

        if (!$entity instanceof Envelope) {
            return;
        }

        $changes = [];
        foreach ($args->getEntityChangeSet() as $field => $change) {
            $changes[$field] = ['old' => $change[0], 'new' => $change[1]];
        }

        if (empty($changes)) {
            return;
        }

        $history = new EnvelopeEditedEvent(
            $entity->getId(),
            $changes
        );

        self::$processingHistory = true;
        $this->eventStore->append($history);
        self::$processingHistory = false;
    }
}
