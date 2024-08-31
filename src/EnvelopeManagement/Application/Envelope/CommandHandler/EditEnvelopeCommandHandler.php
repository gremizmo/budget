<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Envelope\Command\EditEnvelopeCommand;
use App\EnvelopeManagement\Domain\Envelope\Factory\EditEnvelopeFactoryInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;

readonly class EditEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeRepository,
        private EditEnvelopeFactoryInterface $envelopeFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws EditEnvelopeCommandHandlerException
     */
    public function __invoke(EditEnvelopeCommand $command): void
    {
        try {
            $this->envelopeRepository->save(
                $this->envelopeFactory->createFromDto(
                    $command->getEnvelope(),
                    $command->getUpdateEnvelopeDTO(),
                    $command->getParentEnvelope(),
                )
            );
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new EditEnvelopeCommandHandlerException(EditEnvelopeCommandHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
