<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\CommandHandler;

use App\EnvelopeManagement\Application\Command\EditEnvelopeCommand;
use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;

readonly class EditEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
    ) {
    }

    public function __invoke(EditEnvelopeCommand $command): void
    {
        // $this->envelopeRepository->save();
    }
}
