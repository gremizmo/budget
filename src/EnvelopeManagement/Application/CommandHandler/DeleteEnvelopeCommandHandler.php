<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\DeleteEnvelopeCommand;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;

readonly class DeleteEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
    ) {
    }

    public function __invoke(DeleteEnvelopeCommand $deleteEnvelopeCommand): void
    {
        $envelope = $deleteEnvelopeCommand->getEnvelope();
        $envelope->getParent()?->updateAncestorsCurrentBudget(-floatval($envelope->getCurrentBudget()));
        $this->envelopeRepository->delete($envelope);
    }
}
