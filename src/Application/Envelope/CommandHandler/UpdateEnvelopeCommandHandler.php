<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\UpdateEnvelopeCommand;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentException;
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
     * @throws ChildrenTargetBudgetsExceedsParentException
     */
    public function __invoke(UpdateEnvelopeCommand $command): void
    {
        $envelope = $command->getEnvelope();
        $updateEnvelopeDTO = $command->getUpdateEnvelopeDTO();
        $parentEnvelope = $command->getParentEnvelope();

        if ($parentEnvelope && $parentEnvelope->exceedsTargetBudget(floatval($updateEnvelopeDTO->getTargetBudget()))) {
            $this->logger->error(
                ChildrenTargetBudgetsExceedsParentException::MESSAGE,
                [
                    'parentEnvelope' => $parentEnvelope->getId(),
                    'parentEnvelopeTargetBudget' => $parentEnvelope->getTargetBudget(),
                    'currentEnvelopeTargetBudget' => $updateEnvelopeDTO->getTargetBudget(),
                ]
            );
            throw new ChildrenTargetBudgetsExceedsParentException(ChildrenTargetBudgetsExceedsParentException::MESSAGE, 400);
        }

        $this->envelopeRepository->save(
            $this->envelopeFactory->updateEnvelope(
                $envelope,
                $updateEnvelopeDTO,
                $parentEnvelope,
            )
        );
    }
}
