<?php

declare(strict_types=1);

namespace App\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Domain\Envelope\Factory\CreateEnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

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
                    $command->getUser(),
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
