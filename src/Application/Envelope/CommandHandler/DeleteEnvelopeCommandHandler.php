<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class DeleteEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteEnvelopeCommand $deleteEnvelopeCommand): void
    {
        try {
            $this->envelopeRepository->delete($deleteEnvelopeCommand->getEnvelope());
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }
}
