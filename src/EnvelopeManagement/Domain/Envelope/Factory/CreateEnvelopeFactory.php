<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Factory;

use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;

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
        string $userUuid,
    ): EnvelopeInterface {
        try {
            return $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto)
                ->setParentEnvelope($parentEnvelope)
                ->setUserUuid($userUuid)
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
