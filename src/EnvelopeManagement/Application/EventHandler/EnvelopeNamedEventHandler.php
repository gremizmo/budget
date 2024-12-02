<?php

namespace App\EnvelopeManagement\Application\EventHandler;

use App\EnvelopeManagement\Application\Event\EnvelopeNamedEvent;
use App\EnvelopeManagement\Domain\Aggregate\Envelope as Aggregate;
use App\EnvelopeManagement\Domain\EventStore\EventStoreInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\View\Envelope;

readonly class EnvelopeNamedEventHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeCommandRepository,
        private EventStoreInterface $eventStore,
    ) {
    }

    public function __invoke(EnvelopeNamedEvent $event): void
    {
        $events = $this->eventStore->load($event->getAggregateId());

        $aggregate = Aggregate::reconstituteFromEvents(
            array_map(fn ($event) => $event, $events)
        );

        $viewModel = Envelope::create(
            [
                'uuid' => $event->getAggregateId(),
                'created_at' => $aggregate->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $event->occurredOn()->format('Y-m-d H:i:s'),
                'current_budget' => $aggregate->getCurrentBudget()->__toString(),
                'target_budget' => $aggregate->getTargetBudget()->__toString(),
                'title' => $event->getName(),
                'user_uuid' => $aggregate->getUserId()->__toString(),
            ]
        );

        $this->envelopeCommandRepository->save($viewModel);
    }
}
