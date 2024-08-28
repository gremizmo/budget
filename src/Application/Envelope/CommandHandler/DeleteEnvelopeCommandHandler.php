<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;

readonly class DeleteEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
    ) {
    }

    public function __invoke(DeleteEnvelopeCommand $deleteEnvelopeCommand): void
    {
        $envelope = $deleteEnvelopeCommand->getEnvelope();
        $this->updateParentCurrentBudget($envelope);
        $this->envelopeRepository->delete($envelope);
    }

    private function updateParentCurrentBudget(EnvelopeInterface $envelope): void
    {
        $currentBudget = floatval($envelope->getCurrentBudget());
        $this->updateAncestorsCurrentBudget($envelope->getParent(), -$currentBudget);
    }

    private function updateAncestorsCurrentBudget(?EnvelopeInterface $envelope, float $currentBudget): void
    {
        if (null === $envelope) {
            return;
        }

        $envelope->setCurrentBudget(
            \number_format(
                num: \floatval($envelope->getCurrentBudget()) + $currentBudget,
                decimals: 2,
                thousands_separator: ''
            )
        );

        $this->updateAncestorsCurrentBudget($envelope->getParent(), $currentBudget);
    }
}
