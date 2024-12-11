<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\DeleteEnvelopeCommand;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\ValueObject\UserId;
use App\SharedContext\Domain\Repository\EventSourcedRepositoryInterface;

readonly class DeleteEnvelopeCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
    ) {
    }

    public function __invoke(DeleteEnvelopeCommand $command): void
    {
        $events = $this->eventSourcedRepository->get($command->getUuid());
        $aggregate = Envelope::reconstituteFromEvents(array_map(fn ($event) => $event, $events));
        $aggregate->delete(UserId::create($command->getUserUuid()));
        $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
        $aggregate->clearUncommitedEvent();
    }
}
