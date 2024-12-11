<?php

namespace App\EnvelopeManagement\Application\EventHandler;

use App\EnvelopeManagement\Domain\Event\EnvelopeNamedEvent;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\EnvelopeInterface;

readonly class EnvelopeNamedEventHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeCommandRepository,
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ) {
    }

    public function __invoke(EnvelopeNamedEvent $event): void
    {
        $envelope = $this->envelopeQueryRepository->findOneBy(
            ['uuid' => $event->getAggregateId(), 'is_deleted' => false],
        );

        if (!$envelope instanceof EnvelopeInterface) {
            return;
        }

        $envelope->setUpdatedAt($event->occurredOn()->format('Y-m-d H:i:s'));
        $envelope->setName($event->getName());
        $this->envelopeCommandRepository->save($envelope);
    }
}
