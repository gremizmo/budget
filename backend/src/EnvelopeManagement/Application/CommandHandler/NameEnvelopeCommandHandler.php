<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\NameEnvelopeCommand;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeId;
use App\EnvelopeManagement\Domain\ValueObject\EnvelopeName;
use App\EnvelopeManagement\Domain\ValueObject\UserId;
use App\SharedContext\Domain\Repository\EventSourcedRepositoryInterface;

readonly class NameEnvelopeCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
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
            $this->envelopeQueryRepository,
        );
        $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
        $aggregate->clearUncommitedEvent();
    }
}
