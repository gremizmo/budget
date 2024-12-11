<?php

namespace App\EnvelopeManagement\Application\EventHandler;

use App\EnvelopeManagement\Domain\Event\EnvelopeCreatedEvent;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\View\Envelope;

readonly class EnvelopeCreatedEventHandler
{
    public function __construct(private EnvelopeCommandRepositoryInterface $envelopeCommandRepository)
    {
    }

    public function __invoke(EnvelopeCreatedEvent $event): void
    {
        $viewModel = Envelope::create(
            [
                'uuid' => $event->getAggregateId(),
                'created_at' => $event->occurredOn()->format('Y-m-d H:i:s'),
                'updated_at' => $event->occurredOn()->format('Y-m-d H:i:s'),
                'current_budget' => '0.00',
                'target_budget' => $event->getTargetBudget(),
                'name' => $event->getName(),
                'user_uuid' => $event->getUserId(),
                'is_deleted' => false,
            ]
        );
        $this->envelopeCommandRepository->save($viewModel);
    }
}
