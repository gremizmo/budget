<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\CreditEnvelopeCommand;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeCreditMoney;
use App\EnvelopeManagement\Domain\ValueObject\UserId;
use App\SharedContext\Domain\Repository\EventSourcedRepositoryInterface;

readonly class CreditEnvelopeCommandHandler
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
