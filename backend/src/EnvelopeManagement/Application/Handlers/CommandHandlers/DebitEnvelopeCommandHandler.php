<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Handlers\CommandHandlers;

use App\EnvelopeManagement\Application\Commands\DebitEnvelopeCommand;
use App\EnvelopeManagement\Domain\Aggregates\Envelope;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeDebitMoney;
use App\EnvelopeManagement\Domain\ValueObjects\UserId;
use App\SharedContext\Domain\Ports\Inbound\EventSourcedRepositoryInterface;

final readonly class DebitEnvelopeCommandHandler
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
