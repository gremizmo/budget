<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\Factory\CreateEnvelopeFactoryException;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Entity\UserInterface;

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
        CreateEnvelopeDtoInterface $createEnvelopeDto,
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
