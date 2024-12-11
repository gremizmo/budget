<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\DeleteEnvelopeCommand;
use App\EnvelopeManagement\Domain\Adapter\AMQPStreamConnectionInterface;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\EventStore\EventStoreInterface;
use App\EnvelopeManagement\Domain\ValueObject\UserId;

readonly class DeleteEnvelopeCommandHandler
{
    public function __construct(
        private AMQPStreamConnectionInterface $amqpStreamConnection,
        private EventStoreInterface $eventStore,
    ) {
    }

    public function __invoke(DeleteEnvelopeCommand $command): void
    {
        $events = $this->eventStore->load($command->getUuid());
        $aggregate = Envelope::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $aggregate->delete(UserId::create($command->getUserUuid()));
        $this->eventStore->save($aggregate->getUncommittedEvents());
        $this->amqpStreamConnection->publishEvents($aggregate->getUncommittedEvents());
    }
}
