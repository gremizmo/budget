<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Factory;

use App\EnvelopeManagement\Application\Envelope\Dto\EditEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Builder\EditEnvelopeBuilderInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

readonly class EditEnvelopeFactory implements EditEnvelopeFactoryInterface
{
    public function __construct(
        private EditEnvelopeBuilderInterface $editEnvelopeBuilder,
    ) {
    }

    public function createFromDto(
        EnvelopeInterface $envelope,
        EditEnvelopeInputInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        return $this->editEnvelopeBuilder->setEnvelope($envelope)
            ->setUpdateEnvelopeDto($updateEnvelopeDto)
            ->setParentEnvelope($parentEnvelope)
            ->build();
    }
}
