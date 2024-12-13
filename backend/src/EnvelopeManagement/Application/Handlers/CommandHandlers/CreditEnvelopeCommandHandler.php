<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Handlers\CommandHandlers;

use App\EnvelopeManagement\Application\Commands\CreditEnvelopeCommand;
use App\EnvelopeManagement\Domain\Aggregates\Envelope;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeCreditMoney;
use App\EnvelopeManagement\Domain\ValueObjects\UserId;
use App\SharedContext\Domain\Ports\Inbound\EventSourcedRepositoryInterface;

final readonly class CreditEnvelopeCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
    ) {
    }

    public function __invoke(CreditEnvelopeCommand $command): void
    {
        $events = $this->eventSourcedRepository->get($command->getUuid());
        $aggregate = Envelope::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $aggregate->credit(
            EnvelopeCreditMoney::create(
                $command->getCreditMoney(),
            ),
            UserId::create($command->getUserUuid()),
        );
        $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
        $aggregate->clearUncommitedEvent();
    }
}
