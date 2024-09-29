<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Envelope\Command\EditEnvelopeCommand;
use App\EnvelopeManagement\Domain\Envelope\Factory\EditEnvelopeFactoryInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;

readonly class EditEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
        private EditEnvelopeFactoryInterface $envelopeFactory,
    ) {
    }

    public function __invoke(EditEnvelopeCommand $command): void
    {
        $this->envelopeRepository->save(
            $this->envelopeFactory->createFromDto(
                $command->getEnvelope(),
                $command->getUpdateEnvelopeDTO(),
                $command->getParentEnvelope(),
            )
        );
    }
}
