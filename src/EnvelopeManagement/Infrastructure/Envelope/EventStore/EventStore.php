<?php

declare(strict_types=1);

namespace App\EventSourcing;

use App\EnvelopeManagement\Application\Envelope\Event\EnvelopeCreatedEvent;
use App\EnvelopeManagement\Domain\Envelope\Event\EventInterface;
use App\EnvelopeManagement\Domain\Envelope\EventStore\EventStoreInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class EventStore implements EventStoreInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function append(EventInterface $event): void
    {
        $this->connection->insert('event_store', [
            'aggregate_id' => $event->getAggregateId(),
            'payload' => json_encode($event->getPayload()),
            'occurred_on' => $event->getOccurredOn()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @throws Exception
     */
    public function getEventsForAggregate(string $aggregateId): array
    {
        $sql = 'SELECT * FROM event_store WHERE aggregate_id = :aggregate_id ORDER BY occurred_on ASC';
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery(['aggregate_id' => $aggregateId])->fetchAllAssociative();

        return array_map(function ($row) {
            return new EnvelopeCreatedEvent($row['aggregate_id'], json_decode($row['payload'], true));
        }, $result);
    }
}
