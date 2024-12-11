<?php

namespace App\EnvelopeManagement\Application\EventHandler;

use App\EnvelopeManagement\Domain\Event\EnvelopeDebitedEvent;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
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

        $envelope->setUpdatedAt($event->occurredOn()->format('Y-m-d H:i:s'));
        $envelope->setCurrentBudget((string) (
            floatval($envelope->getCurrentBudget()) - floatval($event->getDebitMoney())
        ));
        $this->envelopeCommandRepository->save($envelope);
    }
}
