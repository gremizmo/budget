<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Domain\Envelope\Entity\EnvelopeCollectionInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Factory\EnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class CreateEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeCommandRepository,
        private EnvelopeFactoryInterface $envelopeFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateEnvelopeCommand $command): void
    {
        $envelopeDTO = $command->getCreateEnvelopeDTO();
        $parentEnvelope = $command->getParentEnvelope();

        if ($parentEnvelope) {
            /** @var EnvelopeCollectionInterface $children */
            $children = $parentEnvelope->getChildren();
            $totalChildrenBudget = $children->reduce(
                fn (string $carry, EnvelopeInterface $child) => floatval($carry) + floatval($child->getCurrentBudget()),
                0.0
            ) + floatval($envelopeDTO->getCurrentBudget());

            if ($totalChildrenBudget > floatval($parentEnvelope->getTargetBudget())) {
                throw new \Exception("Total budget of child envelopes exceeds the parent envelope's target budget.");
            }
        }

        $envelope = $this->envelopeFactory->createEnvelope($envelopeDTO, $parentEnvelope);

        try {
            $this->envelopeCommandRepository->save($envelope);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
