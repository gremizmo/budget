<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\UpdateEnvelopeCommand;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Factory\EnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class UpdateEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
        private EnvelopeFactoryInterface $envelopeFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateEnvelopeCommand $command): void
    {
        $envelope = $command->getEnvelope();
        $updateEnvelopeDTO = $command->getUpdateEnvelopeDTO();
        $parentEnvelope = $envelope->getParent();

        if ($parentEnvelope) {
            $totalChildrenBudget = array_reduce(
                $parentEnvelope->getChildren()->filter(fn (EnvelopeInterface $child) => $child !== $envelope)->toArray(),
                fn ($carry, $child) => $carry + floatval($child->getCurrentBudget()),
                0.0
            ) + floatval($updateEnvelopeDTO->getCurrentBudget());

            if ($totalChildrenBudget > floatval($parentEnvelope->getTargetBudget())) {
                throw new \Exception("Total budget of child envelopes exceeds the parent envelope's target budget.");
            }
        }

        $updatedEnvelope = $this->envelopeFactory->updateEnvelope($envelope, $updateEnvelopeDTO);

        try {
            $this->envelopeRepository->save($updatedEnvelope);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
