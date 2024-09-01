<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\CommandHandler;

use App\EnvelopeManagement\Application\Envelope\Command\CreateEnvelopeCommand;
use App\EnvelopeManagement\Domain\Envelope\Factory\CreateEnvelopeFactoryInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;

readonly class CreateEnvelopeCommandHandler
{
    public function __construct(
        private EnvelopeCommandRepositoryInterface $envelopeCommandRepository,
        private CreateEnvelopeFactoryInterface $envelopeFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws CreateEnvelopeCommandHandlerException
     */
    public function __invoke(CreateEnvelopeCommand $command): void
    {
        try {
            $this->envelopeCommandRepository->save(
                $this->envelopeFactory->createFromDto(
                    $command->getCreateEnvelopeDTO(),
                    $command->getParentEnvelope(),
                    $command->getUserId(),
                )
            );
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new CreateEnvelopeCommandHandlerException(CreateEnvelopeCommandHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
