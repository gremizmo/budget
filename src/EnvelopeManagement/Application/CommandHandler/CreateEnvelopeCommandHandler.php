<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\CreateEnvelopeCommand;
use App\EnvelopeManagement\Domain\Adapter\AMQPStreamConnectionInterface;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\EventStore\EventStoreInterface;
use App\EnvelopeManagement\Domain\Exception\EnvelopeAlreadyExistsException;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;

readonly class CreateEnvelopeCommandHandler
{
    public function __construct(
        private AMQPStreamConnectionInterface $amqpStreamConnection,
        private EventStoreInterface $eventStore,
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ) {
    }

    public function __invoke(CreateEnvelopeCommand $command): void
    {
        try {
            $this->eventStore->load($command->getUuid());
        } catch (\RuntimeException $exception) {
            $aggregate = Envelope::create(
                $command->getUuid(),
                $command->getUserUuid(),
                $command->getTargetBudget(),
                $command->getName(),
                $this->envelopeQueryRepository,
            );
            $this->eventStore->save($aggregate->getUncommittedEvents());

            $this->amqpStreamConnection->publishEvents($aggregate->getUncommittedEvents());

            return;
        }

        throw new EnvelopeAlreadyExistsException(EnvelopeAlreadyExistsException::MESSAGE, 400);
    }
}
