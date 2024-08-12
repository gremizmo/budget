<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Builder;

use App\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Validator\CurrentBudgetValidator;
use App\Domain\Envelope\Validator\TargetBudgetValidator;
use App\Domain\User\Entity\UserInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeBuilderTest extends TestCase
{
    private TargetBudgetValidator&MockObject $targetBudgetValidator;
    private CurrentBudgetValidator&MockObject $currentBudgetValidator;
    private CreateEnvelopeBuilder $createEnvelopeBuilder;

    protected function setUp(): void
    {
        $this->targetBudgetValidator = $this->createMock(TargetBudgetValidator::class);
        $this->currentBudgetValidator = $this->createMock(CurrentBudgetValidator::class);
        $this->createEnvelopeBuilder = new CreateEnvelopeBuilder(
            $this->targetBudgetValidator,
            $this->currentBudgetValidator
        );
    }

    public function testSetParentEnvelope(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $result = $this->createEnvelopeBuilder->setParentEnvelope($parentEnvelope);

        $this->assertSame($this->createEnvelopeBuilder, $result);
    }

    public function testSetCreateEnvelopeDto(): void
    {
        $createEnvelopeDto = $this->createMock(CreateEnvelopeDtoInterface::class);
        $result = $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto);

        $this->assertSame($this->createEnvelopeBuilder, $result);
    }

    public function testSetUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $result = $this->createEnvelopeBuilder->setUser($user);

        $this->assertSame($this->createEnvelopeBuilder, $result);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    public function testBuildSuccess(): void
    {
        $createEnvelopeDto = $this->createMock(CreateEnvelopeDtoInterface::class);
        $createEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $createEnvelopeDto->method('getCurrentBudget')->willReturn('500.00');
        $createEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $user = $this->createMock(UserInterface::class);

        $this->targetBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1000.00', null);

        $this->currentBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('500.00', null);

        $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto);
        $this->createEnvelopeBuilder->setUser($user);
        $this->createEnvelopeBuilder->setParentEnvelope(null);

        $envelope = $this->createEnvelopeBuilder->build();

        $this->assertInstanceOf(EnvelopeInterface::class, $envelope);
        $this->assertSame('1000.00', $envelope->getTargetBudget());
        $this->assertSame('500.00', $envelope->getCurrentBudget());
        $this->assertSame('Test Title', $envelope->getTitle());
        $this->assertSame($user, $envelope->getUser());
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    public function testBuildFailureDueToCurrentBudgetExceedsParentTarget(): void
    {
        $createEnvelopeDto = $this->createMock(CreateEnvelopeDtoInterface::class);
        $createEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $createEnvelopeDto->method('getCurrentBudget')->willReturn('1500.00');
        $createEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('1000.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('500.00');

        $this->targetBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1000.00', $parentEnvelope);

        $this->currentBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1500.00', $parentEnvelope)
            ->willThrowException(new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400));

        $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto);
        $this->createEnvelopeBuilder->setParentEnvelope($parentEnvelope);

        $this->expectException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->createEnvelopeBuilder->build();
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function testUpdateParentCurrentBudgetThrowsException(): void
    {
        $createEnvelopeDto = $this->createMock(CreateEnvelopeDtoInterface::class);
        $createEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $createEnvelopeDto->method('getCurrentBudget')->willReturn('1500.00');
        $createEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $parentEnvelope = new Envelope();
        $parentEnvelope->setId(1);
        $parentEnvelope->setTargetBudget('1000.00');
        $parentEnvelope->setCurrentBudget('500.00');

        $user = $this->createMock(UserInterface::class);

        $envelope = new Envelope();
        $envelope->setId(2);
        $envelope->setCurrentBudget('500.00');
        $envelope->setParent($parentEnvelope);

        $this->targetBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1000.00', $parentEnvelope);

        $this->currentBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1500.00', $parentEnvelope);

        $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto);
        $this->createEnvelopeBuilder->setUser($user);
        $this->createEnvelopeBuilder->setParentEnvelope($parentEnvelope);

        $this->expectException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->createEnvelopeBuilder->build();
    }
}
