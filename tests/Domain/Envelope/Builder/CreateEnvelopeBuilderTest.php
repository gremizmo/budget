<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Builder;

use App\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\Domain\Envelope\Builder\CreateEnvelopeBuilderException;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Envelope\Validator\EditEnvelopeCurrentBudgetValidator;
use App\Domain\Envelope\Validator\EditEnvelopeTargetBudgetValidator;
use App\Domain\Envelope\Validator\EditEnvelopeTitleValidator;
use App\Domain\Shared\Model\UserInterface;
use App\Infra\Http\Rest\Envelope\Entity\Envelope;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeBuilderTest extends TestCase
{
    private EditEnvelopeTargetBudgetValidator&MockObject $targetBudgetValidator;
    private EditEnvelopeCurrentBudgetValidator&MockObject $currentBudgetValidator;
    private EditEnvelopeTitleValidator&MockObject $titleValidator;
    private CreateEnvelopeBuilder $createEnvelopeBuilder;

    protected function setUp(): void
    {
        $this->targetBudgetValidator = $this->createMock(EditEnvelopeTargetBudgetValidator::class);
        $this->currentBudgetValidator = $this->createMock(EditEnvelopeCurrentBudgetValidator::class);
        $this->titleValidator = $this->createMock(EditEnvelopeTitleValidator::class);
        $this->createEnvelopeBuilder = new CreateEnvelopeBuilder(
            $this->targetBudgetValidator,
            $this->currentBudgetValidator,
            $this->titleValidator,
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
        $createEnvelopeDto = $this->createMock(CreateEnvelopeInputInterface::class);
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
     * @throws CreateEnvelopeBuilderException
     */
    public function testBuildSuccess(): void
    {
        $createEnvelopeDto = $this->createMock(CreateEnvelopeInputInterface::class);
        $createEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $createEnvelopeDto->method('getCurrentBudget')->willReturn('500.00');
        $createEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $user = $this->createMock(UserInterface::class);

        $this->targetBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1000.00', null);

        $this->currentBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('500.00', '1000.00', null, null);

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
     * @throws CreateEnvelopeBuilderException
     */
    public function testBuildFailureDueToCurrentBudgetExceedsParentTarget(): void
    {
        $createEnvelopeDto = $this->createMock(CreateEnvelopeInputInterface::class);
        $createEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $createEnvelopeDto->method('getCurrentBudget')->willReturn('1500.00');
        $createEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $user = $this->createMock(UserInterface::class);

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('1000.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('500.00');
        $this->createEnvelopeBuilder->setUser($user);

        $this->targetBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1000.00', $parentEnvelope);

        $this->currentBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1500.00', '1000.00', $parentEnvelope)
            ->willThrowException(new CreateEnvelopeBuilderException(CreateEnvelopeBuilderException::MESSAGE, 400));

        $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto);
        $this->createEnvelopeBuilder->setParentEnvelope($parentEnvelope);

        $this->expectException(CreateEnvelopeBuilderException::class);

        $this->createEnvelopeBuilder->build();
    }

    /**
     * @throws CreateEnvelopeBuilderException
     */
    public function testUpdateParentCurrentBudgetThrowsException(): void
    {
        $createEnvelopeDto = $this->createMock(CreateEnvelopeInputInterface::class);
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
            ->with('1500.00', '1000.00', $parentEnvelope);

        $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto);
        $this->createEnvelopeBuilder->setUser($user);
        $this->createEnvelopeBuilder->setParentEnvelope($parentEnvelope);

        $this->expectException(CreateEnvelopeBuilderException::class);

        $this->createEnvelopeBuilder->build();
    }
}
