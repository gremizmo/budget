<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Factory;

use App\Domain\Envelope\Dto\CreateEnvelopeDto;
use App\Domain\Envelope\Dto\UpdateEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Factory\EnvelopeFactory;
use App\Domain\Shared\Adapter\UuidGeneratorInterface;
use PHPUnit\Framework\TestCase;

class EnvelopeFactoryTest extends TestCase
{
    private EnvelopeFactory $envelopeFactory;

    protected function setUp(): void
    {
        $uuidGenerator = $this->createMock(UuidGeneratorInterface::class);
        $uuidGenerator->method('generateUuid')->willReturn('uuid');
        $this->envelopeFactory = new EnvelopeFactory($uuidGenerator);
    }

    public function testCreateEnvelope(): void
    {
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '1000.00', '2000.00');
        $parentEnvelope = new Envelope();

        $envelope = $this->envelopeFactory->createEnvelope($createEnvelopeDto, $parentEnvelope);

        $this->assertInstanceOf(Envelope::class, $envelope);
        $this->assertSame($parentEnvelope, $envelope->getParent());
        $this->assertSame('1000.00', $envelope->getCurrentBudget());
        $this->assertSame('2000.00', $envelope->getTargetBudget());
        $this->assertSame('Test Title', $envelope->getTitle());
        $this->assertInstanceOf(\DateTimeImmutable::class, $envelope->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $envelope->getUpdatedAt());
        $this->assertSame('uuid', $envelope->getCreatedBy());
        $this->assertSame('uuid', $envelope->getUpdatedBy());
    }

    public function testUpdateEnvelope(): void
    {
        $envelope = new Envelope();
        $updateEnvelopeDto = new UpdateEnvelopeDto('Updated Title', '1500.00', '2500.00');
        $parentEnvelope = new Envelope();

        $updatedEnvelope = $this->envelopeFactory->updateEnvelope($envelope, $updateEnvelopeDto, $parentEnvelope);

        $this->assertSame('Updated Title', $updatedEnvelope->getTitle());
        $this->assertSame('1500.00', $updatedEnvelope->getCurrentBudget());
        $this->assertSame('2500.00', $updatedEnvelope->getTargetBudget());
        $this->assertSame($parentEnvelope, $updatedEnvelope->getParent());
        $this->assertInstanceOf(\DateTime::class, $updatedEnvelope->getUpdatedAt());
        $this->assertSame('uuid', $updatedEnvelope->getUpdatedBy());
    }
}
