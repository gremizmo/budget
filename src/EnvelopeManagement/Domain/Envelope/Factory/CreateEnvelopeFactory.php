<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Factory;

use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Builder\CreateEnvelopeBuilderInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

readonly class CreateEnvelopeFactory implements CreateEnvelopeFactoryInterface
{
    public function __construct(
        private CreateEnvelopeBuilderInterface $createEnvelopeBuilder,
    ) {
    }

    public function createFromDto(
        CreateEnvelopeInputInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope,
        string $userUuid,
    ): EnvelopeInterface {
        return $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto)
            ->setParentEnvelope($parentEnvelope)
            ->setUserUuid($userUuid)
            ->build();
    }
}
