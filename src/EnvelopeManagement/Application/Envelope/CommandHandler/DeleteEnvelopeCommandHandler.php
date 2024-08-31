<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Envelope\Command\DeleteEnvelopeCommand;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;

readonly class DeleteEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws DeleteEnvelopeCommandHandlerException
     */
    public function __invoke(DeleteEnvelopeCommand $deleteEnvelopeCommand): void
    {
        try {
            $envelope = $deleteEnvelopeCommand->getEnvelope();
            $envelope->getParent()?->updateAncestorsCurrentBudget(-floatval($envelope->getCurrentBudget()));
            $this->envelopeRepository->delete($envelope);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new DeleteEnvelopeCommandHandlerException(DeleteEnvelopeCommandHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
