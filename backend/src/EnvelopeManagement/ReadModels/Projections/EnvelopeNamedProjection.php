<?php

namespace App\EnvelopeManagement\ReadModels\Projections;

use App\EnvelopeManagement\Domain\Events\EnvelopeNamedEvent;
use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\ReadModels\Views\EnvelopeViewInterface;

final readonly class EnvelopeNamedProjection
{
    public function __construct(private EnvelopeRepositoryInterface $envelopeRepository)
    {
    }

    public function __invoke(EnvelopeNamedEvent $event): void
    {
        $envelope = $this->envelopeRepository->findOneBy(
            ['uuid' => $event->getAggregateId(), 'is_deleted' => false],
        );

        if (!$envelope instanceof EnvelopeViewInterface) {
            return;
        }

        $envelope->setUpdatedAt(\DateTime::createFromImmutable($event->occurredOn()));
        $envelope->setName($event->getName());
        $this->envelopeRepository->save($envelope);
    }
}
