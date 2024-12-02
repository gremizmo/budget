<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\NameEnvelopeCommand;
use App\EnvelopeManagement\Domain\Adapter\AMQPStreamConnectionInterface;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\EventStore\EventStoreInterface;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeName;
use App\EnvelopeManagement\Domain\ValueObject\UserId;

readonly class NameEnvelopeCommandHandler
{
    public function __construct(
        private EventStoreInterface $eventStore,
        private AMQPStreamConnectionInterface $amqpStreamConnection,
    ) {
    }

    public function __invoke(NameEnvelopeCommand $command): void
    {
        $events = $this->eventStore->load($command->getUuid());

        $aggregate = Envelope::reconstituteFromEvents(
            array_map(fn ($event) => $event, $events)
        );

        $aggregate->rename(EnvelopeName::withName($command->getName()), UserId::withUuid($command->getUserUuid()));

        $this->eventStore->save($aggregate->getUncommittedEvents());

        $this->amqpStreamConnection->publishEvents($aggregate->getUncommittedEvents());
    }
}
