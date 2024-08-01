<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\EditEnvelopeCommand;
use App\Domain\Envelope\Factory\EnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Envelope\Service\TargetBudgetValidatorInterface;

readonly class EditEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
        private EnvelopeFactoryInterface $envelopeFactory,
        private TargetBudgetValidatorInterface $targetBudgetValidator,
    ) {
    }

    public function __invoke(EditEnvelopeCommand $command): void
    {
        $updateEnvelopeDTO = $command->getUpdateEnvelopeDTO();
        $parentEnvelope = $command->getParentEnvelope();
        $envelope = $command->getEnvelope();

        $this->targetBudgetValidator->validate(
            targetBudget: floatval($updateEnvelopeDTO->getTargetBudget()),
            parentEnvelope: $parentEnvelope,
            currentEnvelope: $envelope
        );

        $this->envelopeRepository->save(
            $this->envelopeFactory->updateEnvelope(
                $envelope,
                $updateEnvelopeDTO,
                $parentEnvelope,
            )
        );
    }
}
