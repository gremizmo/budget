<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\DebitEnvelopeCommand;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeDebitMoney;
use App\EnvelopeManagement\Domain\ValueObject\UserId;
use App\SharedContext\Domain\Repository\EventSourcedRepositoryInterface;

readonly class DebitEnvelopeCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
    ) {
    }

    public function __invoke(DebitEnvelopeCommand $command): void
    {
        $events = $this->eventSourcedRepository->get($command->getUuid());
        $aggregate = Envelope::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $aggregate->debit(
            EnvelopeDebitMoney::create(
                $command->getDebitMoney(),
            ),
            UserId::create($command->getUserUuid()),
        );
        $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
        $aggregate->clearUncommitedEvent();
    }
}
