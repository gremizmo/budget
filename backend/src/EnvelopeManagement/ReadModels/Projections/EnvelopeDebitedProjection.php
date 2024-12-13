<?php

namespace App\EnvelopeManagement\ReadModels\Projections;

use App\EnvelopeManagement\Domain\Events\EnvelopeDebitedEvent;
use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\ReadModels\Views\EnvelopeViewInterface;

final readonly class EnvelopeDebitedProjection
{
    public function __construct(private EnvelopeRepositoryInterface $envelopeRepository)
    {
    }

    public function __invoke(EnvelopeDebitedEvent $event): void
    {
        $envelope = $this->envelopeRepository->findOneBy(
            ['uuid' => $event->getAggregateId(), 'is_deleted' => false],
        );

        if (!$envelope instanceof EnvelopeViewInterface) {
            return;
        }

        $envelope->setUpdatedAt(\DateTime::createFromImmutable($event->occurredOn()));
        $envelope->setCurrentBudget((string) (
            floatval($envelope->getCurrentBudget()) - floatval($event->getDebitMoney())
        ));
        $this->envelopeRepository->save($envelope);
    }
}
