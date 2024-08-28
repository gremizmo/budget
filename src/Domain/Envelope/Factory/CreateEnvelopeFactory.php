<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\Domain\Envelope\Exception\Factory\CreateEnvelopeFactoryException;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\Shared\Model\UserInterface;

readonly class CreateEnvelopeFactory implements CreateEnvelopeFactoryInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private CreateEnvelopeBuilder $createEnvelopeBuilder,
    ) {
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function createFromDto(
        CreateEnvelopeInputInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope,
        UserInterface $user,
    ): EnvelopeInterface {
        try {
            return $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto)
                ->setParentEnvelope($parentEnvelope)
                ->setUser($user)
                ->build();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new CreateEnvelopeFactoryException(CreateEnvelopeFactoryException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
