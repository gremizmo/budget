<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\CreateEnvelopeCommand;
use App\EnvelopeManagement\Domain\Aggregate\Envelope;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\SharedContext\Domain\Repository\EventSourcedRepositoryInterface;

final readonly class CreateEnvelopeCommandHandler
{
    public function __construct(
        private EventSourcedRepositoryInterface $eventSourcedRepository,
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ) {
    }

    public function __invoke(CreateEnvelopeCommand $command): void
    {
        try {
            $this->eventSourcedRepository->get($command->getUuid());
        } catch (\RuntimeException $exception) {
            $aggregate = Envelope::create(
                $command->getUuid(),
                $command->getUserUuid(),
                $command->getTargetBudget(),
                $command->getName(),
                $this->envelopeQueryRepository,
            );
            $this->eventSourcedRepository->save($aggregate->getUncommittedEvents());
            $aggregate->clearUncommitedEvent();

            return;
        }

        throw new EnvelopeAlreadyExistsException(EnvelopeAlreadyExistsException::MESSAGE, 400);
    }
}
