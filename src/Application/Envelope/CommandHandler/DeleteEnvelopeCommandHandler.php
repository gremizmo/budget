<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;

readonly class DeleteEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
    ) {
    }

    public function __invoke(DeleteEnvelopeCommand $deleteEnvelopeCommand): void
    {
        $this->envelopeRepository->delete($deleteEnvelopeCommand->getEnvelope());
    }
}
