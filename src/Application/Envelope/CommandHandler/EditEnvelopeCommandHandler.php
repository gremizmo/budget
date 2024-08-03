<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\EditEnvelopeCommand;
use App\Domain\Envelope\Factory\EditEnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;

readonly class EditEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
        private EditEnvelopeFactoryInterface $envelopeFactory,
    ) {
    }

    public function __invoke(EditEnvelopeCommand $command): void
    {
        $updateEnvelopeDTO = $command->getUpdateEnvelopeDTO();
        $parentEnvelope = $command->getParentEnvelope();
        $envelope = $command->getEnvelope();

        $this->envelopeRepository->save(
            $this->envelopeFactory->createFromDto(
                $envelope,
                $updateEnvelopeDTO,
                $parentEnvelope,
            )
        );
    }
}
