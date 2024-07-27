<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\UpdateEnvelopeCommand;
use App\Domain\Envelope\Factory\EnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;

readonly class UpdateEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
        private EnvelopeFactoryInterface $envelopeFactory,
    ) {
    }

    public function __invoke(UpdateEnvelopeCommand $command): void
    {
        $this->envelopeRepository->save(
            $this->envelopeFactory->updateEnvelope(
                $command->getEnvelope(),
                $command->getUpdateEnvelopeDTO(),
            )
        );
    }
}
