<?php

namespace App\EnvelopeManagement\ReadModels\Projections;

use App\EnvelopeManagement\Domain\Events\EnvelopeCreatedEvent;
use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\ReadModels\Views\EnvelopeView;

final readonly class EnvelopeCreatedProjection
{
    public function __construct(private EnvelopeRepositoryInterface $envelopeCommandRepository)
    {
    }

    public function __invoke(EnvelopeCreatedEvent $event): void
    {
        $this->envelopeCommandRepository->save(
            (new EnvelopeView())
                ->setUuid($event->getAggregateId())
                ->setCreatedAt($event->occurredOn())
                ->setUpdatedAt(\DateTime::createFromImmutable($event->occurredOn()))
                ->setIsDeleted(false)
                ->setTargetBudget($event->getTargetBudget())
                ->setCurrentBudget('0.00')
                ->setName($event->getName())
                ->setUserUuid($event->getUserId())
        );
    }
}
