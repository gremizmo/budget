<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\DebitEnvelopeCommand;
use App\EnvelopeManagement\Domain\Adapter\AMQPStreamConnectionInterface;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\EventStore\EventStoreInterface;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeDebitMoney;
use App\EnvelopeManagement\Domain\ValueObject\UserId;

readonly class DebitEnvelopeCommandHandler
{
    public function __construct(
        private EventStoreInterface $eventStore,
        private AMQPStreamConnectionInterface $amqpStreamConnection,
    ) {
    }

    public function __invoke(DebitEnvelopeCommand $command): void
    {
        $events = $this->eventStore->load($command->getUuid());
        $aggregate = Envelope::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $aggregate->debit(
            EnvelopeDebitMoney::create(
                $command->getDebitMoney(),
            ),
            UserId::create($command->getUserUuid()),
        );
        $this->eventStore->save($aggregate->getUncommittedEvents());
        $this->amqpStreamConnection->publishEvents($aggregate->getUncommittedEvents());
    }
}
