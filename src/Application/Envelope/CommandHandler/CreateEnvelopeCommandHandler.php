<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Domain\Envelope\Factory\CreateEnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;

readonly class CreateEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeCommandRepository,
        private CreateEnvelopeFactoryInterface $envelopeFactory,
    ) {
    }

    public function __invoke(CreateEnvelopeCommand $command): void
    {
        $this->envelopeCommandRepository->save(
            $this->envelopeFactory->createFromDto(
                $command->getCreateEnvelopeDTO(),
                $command->getParentEnvelope(),
                $command->getUser(),
            )
        );
    }
}
