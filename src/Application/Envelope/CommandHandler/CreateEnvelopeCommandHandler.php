<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentException;
use App\Domain\Envelope\Factory\EnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Envelope\Service\TargetBudgetValidatorInterface;

readonly class CreateEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeCommandRepository,
        private EnvelopeFactoryInterface $envelopeFactory,
        private TargetBudgetValidatorInterface $targetBudgetValidator,
    ) {
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentException
     */
    public function __invoke(CreateEnvelopeCommand $command): void
    {
        $createEnvelopeDTO = $command->getCreateEnvelopeDTO();
        $parentEnvelope = $command->getParentEnvelope();

        $this->targetBudgetValidator->validate(
            targetBudget: floatval($createEnvelopeDTO->getTargetBudget()),
            parentEnvelope: $parentEnvelope
        );

        $this->envelopeCommandRepository->save(
            $this->envelopeFactory->createEnvelope(
                $createEnvelopeDTO,
                $parentEnvelope,
                $command->getUser(),
            )
        );
    }
}
