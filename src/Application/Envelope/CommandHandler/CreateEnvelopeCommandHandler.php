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
        $createEnvelopeDTO = $command->getCreateEnvelopeDTO();
        $parentEnvelope = $command->getParentEnvelope();

        $this->envelopeCommandRepository->save(
            $this->envelopeFactory->createFromDto(
                $createEnvelopeDTO,
                $parentEnvelope,
                $command->getUser(),
            )
        );
    }
}
