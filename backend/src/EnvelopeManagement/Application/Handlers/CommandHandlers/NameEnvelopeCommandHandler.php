<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Handlers\CommandHandlers;

use App\EnvelopeManagement\Application\Commands\NameEnvelopeCommand;
use App\EnvelopeManagement\Domain\Aggregates\Envelope;
use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeId;
use App\EnvelopeManagement\Domain\ValueObjects\EnvelopeName;
use App\EnvelopeManagement\Domain\ValueObjects\UserId;
use App\SharedContext\Domain\Ports\Inbound\EventSourcedRepositoryInterface;

final readonly class NameEnvelopeCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
        private EnvelopeRepositoryInterface $envelopeRepository,
    ) {
    }

    public function __invoke(NameEnvelopeCommand $command): void
    {
        $events = $this->eventSourcedRepository->get($command->getUuid());
        $aggregate = Envelope::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $aggregate->rename(
            EnvelopeName::create($command->getName()),
            UserId::create($command->getUserUuid()),
            EnvelopeId::create($command->getUuid()),
            $this->envelopeRepository,
        );
        $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
        $aggregate->clearUncommitedEvent();
    }
}
