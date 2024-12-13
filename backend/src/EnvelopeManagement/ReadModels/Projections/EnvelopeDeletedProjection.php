<?php

namespace App\EnvelopeManagement\ReadModels\Projections;

use App\EnvelopeManagement\Domain\Events\EnvelopeDeletedEvent;
use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\ReadModels\Views\EnvelopeViewInterface;

final readonly class EnvelopeDeletedProjection
{
    public function __construct(private EnvelopeRepositoryInterface $envelopeRepository)
    {
    }

    public function __invoke(EnvelopeDeletedEvent $event): void
    {
        $envelope = $this->envelopeRepository->findOneBy(
            ['uuid' => $event->getAggregateId(), 'is_deleted' => false],
        );

        if (!$envelope instanceof EnvelopeViewInterface) {
            return;
        }

        $envelope->setUpdatedAt(\DateTime::createFromImmutable($event->occurredOn()));
        $envelope->setIsDeleted($event->isDeleted());
        $this->envelopeRepository->save($envelope);
    }
}
