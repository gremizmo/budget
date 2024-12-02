<?php

namespace App\EnvelopeManagement\Application\EventHandler;

use App\EnvelopeManagement\Domain\Event\EnvelopeDebitedEvent;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\Envelope;
use App\EnvelopeManagement\Domain\View\EnvelopeInterface;

readonly class EnvelopeDebitedEventHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeCommandRepository,
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ) {
    }

    public function __invoke(EnvelopeDebitedEvent $event): void
    {
        $envelope = $this->envelopeQueryRepository->findOneBy(
            ['uuid' => $event->getAggregateId(), 'is_deleted' => false],
        );

        if (!$envelope instanceof EnvelopeInterface) {
            return;
        }

        $viewModel = Envelope::create(
            [
                'uuid' => $event->getAggregateId(),
                'created_at' => $envelope->getCreatedAt(),
                'updated_at' => $event->occurredOn()->format('Y-m-d H:i:s'),
                'current_budget' => (string) (
                    floatval($envelope->getCurrentBudget()) + floatval($event->getDebitMoney())
                ),
                'target_budget' => $envelope->getTargetBudget(),
                'name' => $envelope->getName(),
                'user_uuid' => $envelope->getUserUuid(),
                'is_deleted' => false,
            ]
        );

        $this->envelopeCommandRepository->save($viewModel);
    }
}
