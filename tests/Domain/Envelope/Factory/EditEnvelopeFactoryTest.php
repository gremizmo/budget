<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\EditEnvelopeBuilderInterface;
use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\SelfParentEnvelopeException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Factory\EditEnvelopeFactory;
use App\Domain\Shared\Adapter\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EditEnvelopeFactoryTest extends TestCase
{
    private LoggerInterface&MockObject $logger;
    private EditEnvelopeBuilderInterface&MockObject $editEnvelopeBuilder;
    private EditEnvelopeFactory $editEnvelopeFactory;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->editEnvelopeBuilder = $this->createMock(EditEnvelopeBuilderInterface::class);
        $this->editEnvelopeFactory = new EditEnvelopeFactory(
            $this->logger,
            $this->editEnvelopeBuilder
        );
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testCreateFromDtoSuccess(): void
    {
        $envelope = $this->createMock(EnvelopeInterface::class);
        $updateEnvelopeDto = $this->createMock(UpdateEnvelopeDtoInterface::class);
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setEnvelope')
            ->with($envelope)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setUpdateEnvelopeDto')
            ->with($updateEnvelopeDto)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setParentEnvelope')
            ->with($parentEnvelope)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('build')
            ->willReturn($envelope);

        $result = $this->editEnvelopeFactory->createFromDto($envelope, $updateEnvelopeDto, $parentEnvelope);

        $this->assertSame($envelope, $result);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testCreateFromDtoFailureDueToChildrenTargetBudgetsExceedsParent(): void
    {
        $envelope = $this->createMock(EnvelopeInterface::class);
        $updateEnvelopeDto = $this->createMock(UpdateEnvelopeDtoInterface::class);
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setEnvelope')
            ->with($envelope)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setUpdateEnvelopeDto')
            ->with($updateEnvelopeDto)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setParentEnvelope')
            ->with($parentEnvelope)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('build')
            ->willThrowException(new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->isType('string'),
                $this->callback(fn ($context) => ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class === $context['exception'])
            );

        $this->expectException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class);

        $this->editEnvelopeFactory->createFromDto($envelope, $updateEnvelopeDto, $parentEnvelope);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testCreateFromDtoFailureDueToParentCurrentBudgetExceedsTarget(): void
    {
        $envelope = $this->createMock(EnvelopeInterface::class);
        $updateEnvelopeDto = $this->createMock(UpdateEnvelopeDtoInterface::class);
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setEnvelope')
            ->with($envelope)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setUpdateEnvelopeDto')
            ->with($updateEnvelopeDto)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('setParentEnvelope')
            ->with($parentEnvelope)
            ->willReturn($this->editEnvelopeBuilder);

        $this->editEnvelopeBuilder->expects($this->once())
            ->method('build')
            ->willThrowException(new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::class, 400));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->isType('string'),
                $this->callback(fn ($context) => EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::class === $context['exception'])
            );

        $this->expectException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->editEnvelopeFactory->createFromDto($envelope, $updateEnvelopeDto, $parentEnvelope);
    }
}
