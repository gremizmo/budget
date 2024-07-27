<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Shared\Adapter\UuidGeneratorInterface;

readonly class EnvelopeFactory implements EnvelopeFactoryInterface
{
    public function __construct(private UuidGeneratorInterface $uuidGenerator)
    {
    }

    public function createEnvelope(
        CreateEnvelopeDtoInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope
    ): EnvelopeInterface {
        $envelope = new Envelope();

        $envelope->setParent($parentEnvelope)
            ->setCurrentBudget($createEnvelopeDto->getCurrentBudget())
            ->setTargetBudget($createEnvelopeDto->getTargetBudget())
            ->setTitle($createEnvelopeDto->getTitle())
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setUpdatedAt(new \DateTime('now'))
            ->setCreatedBy($this->uuidGenerator->generateUuid())
            ->setUpdatedBy($this->uuidGenerator->generateUuid());

        return $envelope;
    }

    public function updateEnvelope(
        EnvelopeInterface $envelope,
        UpdateEnvelopeDtoInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        $envelope->setTitle($updateEnvelopeDto->getTitle())
            ->setCurrentBudget($updateEnvelopeDto->getCurrentBudget())
            ->setTargetBudget($updateEnvelopeDto->getTargetBudget())
            ->setParent($parentEnvelope)
            ->setUpdatedAt(new \DateTime('now'))
            ->setUpdatedBy($this->uuidGenerator->generateUuid());

        return $envelope;
    }
}
