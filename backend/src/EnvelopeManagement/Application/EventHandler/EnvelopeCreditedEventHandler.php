<?php

namespace App\EnvelopeManagement\Application\EventHandler;

use App\EnvelopeManagement\Domain\Event\EnvelopeCreditedEvent;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\EnvelopeInterface;

readonly class EnvelopeCreditedEventHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeCommandRepository,
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ) {
    }

    public function __invoke(EnvelopeCreditedEvent $event): void
    {
        $envelope = $this->envelopeQueryRepository->findOneBy(
            ['uuid' => $event->getAggregateId(), 'is_deleted' => false],
        );

        if (!$envelope instanceof EnvelopeInterface) {
            return;
        }

        $envelope->setUpdatedAt($event->occurredOn()->format('Y-m-d H:i:s'));
        $envelope->setCurrentBudget((string) (
            floatval($envelope->getCurrentBudget()) + floatval($event->getCreditMoney())
        ));
        $this->envelopeCommandRepository->save($envelope);
    }
}
