<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Builder;

use App\Application\Envelope\Dto\EditEnvelopeInputInterface;
use App\Domain\Envelope\Builder\EditEnvelopeBuilder;
use App\Domain\Envelope\Exception\ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\CurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\CurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\Domain\Envelope\Exception\SelfParentEnvelopeException;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Envelope\Validator\EditEnvelopeCurrentBudgetValidator;
use App\Domain\Envelope\Validator\EditEnvelopeTargetBudgetValidator;
use App\Domain\Envelope\Validator\EditEnvelopeTitleValidator;
use App\Infra\Http\Rest\Envelope\Entity\Envelope;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EditEnvelopeBuilderTest extends TestCase
{
    private EditEnvelopeTargetBudgetValidator&MockObject $targetBudgetValidator;
    private EditEnvelopeCurrentBudgetValidator&MockObject $currentBudgetValidator;
    private EditEnvelopeBuilder $editEnvelopeBuilder;
    private EditEnvelopeTitleValidator&MockObject $titleValidator;

    protected function setUp(): void
    {
        $this->targetBudgetValidator = $this->createMock(EditEnvelopeTargetBudgetValidator::class);
        $this->currentBudgetValidator = $this->createMock(EditEnvelopeCurrentBudgetValidator::class);
        $this->titleValidator = $this->createMock(EditEnvelopeTitleValidator::class);
        $this->editEnvelopeBuilder = new EditEnvelopeBuilder(
            $this->targetBudgetValidator,
            $this->currentBudgetValidator,
            $this->titleValidator,
        );
    }

    public function testSetParentEnvelope(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $result = $this->editEnvelopeBuilder->setParentEnvelope($parentEnvelope);

        $this->assertSame($this->editEnvelopeBuilder, $result);
    }

    public function testSetUpdateEnvelopeDto(): void
    {
        $updateEnvelopeDto = $this->createMock(EditEnvelopeInputInterface::class);
        $result = $this->editEnvelopeBuilder->setUpdateEnvelopeDto($updateEnvelopeDto);

        $this->assertSame($this->editEnvelopeBuilder, $result);
    }

    public function testSetEnvelope(): void
    {
        $envelope = $this->createMock(EnvelopeInterface::class);
        $result = $this->editEnvelopeBuilder->setEnvelope($envelope);

        $this->assertSame($this->editEnvelopeBuilder, $result);
    }

    /**
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeTitleAlreadyExistsForUserException
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws CurrentBudgetExceedsEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testBuildSuccess(): void
    {
        $updateEnvelopeDto = $this->createMock(EditEnvelopeInputInterface::class);
        $updateEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $updateEnvelopeDto->method('getCurrentBudget')->willReturn('500.00');
        $updateEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getId')->willReturn(1);
        $parentEnvelope->method('getTargetBudget')->willReturn('1000.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('500.00');

        $envelope = $this->createMock(EnvelopeInterface::class);
        $envelope->method('getId')->willReturn(2);
        $envelope->method('getTargetBudget')->willReturn('1000.00');
        $envelope->method('getCurrentBudget')->willReturn('500.00');
        $envelope->method('getParent')->willReturn($parentEnvelope);
        $envelope->method('getTitle')->willReturn('Test Title');

        $this->targetBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1000.00', $parentEnvelope, $envelope);

        $this->currentBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('500.00', '1000.00', $parentEnvelope);

        $this->editEnvelopeBuilder->setUpdateEnvelopeDto($updateEnvelopeDto);
        $this->editEnvelopeBuilder->setParentEnvelope($parentEnvelope);
        $this->editEnvelopeBuilder->setEnvelope($envelope);

        $result = $this->editEnvelopeBuilder->build();

        $this->assertSame($envelope, $result);
        $this->assertSame('1000.00', $envelope->getTargetBudget());
        $this->assertSame('500.00', $envelope->getCurrentBudget());
        $this->assertSame('Test Title', $envelope->getTitle());
    }

    /**
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeTitleAlreadyExistsForUserException
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws CurrentBudgetExceedsEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testSelfParentEnvelopeException(): void
    {
        $updateEnvelopeDto = $this->createMock(EditEnvelopeInputInterface::class);
        $updateEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $updateEnvelopeDto->method('getCurrentBudget')->willReturn('500.00');
        $updateEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $envelope = $this->createMock(EnvelopeInterface::class);
        $envelope->method('getCurrentBudget')->willReturn('500.00');
        $envelope->method('getParent')->willReturn($envelope);

        $this->targetBudgetValidator->expects($this->never())
            ->method('validate');

        $this->currentBudgetValidator->expects($this->never())
            ->method('validate');

        $this->editEnvelopeBuilder->setUpdateEnvelopeDto($updateEnvelopeDto);
        $this->editEnvelopeBuilder->setParentEnvelope($envelope);
        $this->editEnvelopeBuilder->setEnvelope($envelope);

        $this->expectException(SelfParentEnvelopeException::class);

        $this->editEnvelopeBuilder->build();
    }

    /**
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeTitleAlreadyExistsForUserException
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws CurrentBudgetExceedsEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testBuildFailureDueToCurrentBudgetExceedsParentTarget(): void
    {
        $updateEnvelopeDto = $this->createMock(EditEnvelopeInputInterface::class);
        $updateEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $updateEnvelopeDto->method('getCurrentBudget')->willReturn('1500.00');
        $updateEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getId')->willReturn(1);
        $parentEnvelope->method('getTargetBudget')->willReturn('1000.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('500.00');

        $envelope = $this->createMock(EnvelopeInterface::class);
        $envelope->method('getCurrentBudget')->willReturn('500.00');
        $envelope->method('getParent')->willReturn($parentEnvelope);
        $envelope->method('getId')->willReturn(2);

        $this->targetBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1000.00', $parentEnvelope, $envelope);

        $this->currentBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1500.00', '1000.00', $parentEnvelope)
            ->willThrowException(new CurrentBudgetExceedsParentEnvelopeTargetBudgetException(CurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400));

        $this->editEnvelopeBuilder->setUpdateEnvelopeDto($updateEnvelopeDto);
        $this->editEnvelopeBuilder->setParentEnvelope($parentEnvelope);
        $this->editEnvelopeBuilder->setEnvelope($envelope);

        $this->expectException(CurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->editEnvelopeBuilder->build();
    }

    /**
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeTitleAlreadyExistsForUserException
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws CurrentBudgetExceedsEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testUpdateParentCurrentBudgetThrowsException(): void
    {
        $updateEnvelopeDto = $this->createMock(EditEnvelopeInputInterface::class);
        $updateEnvelopeDto->method('getTargetBudget')->willReturn('1000.00');
        $updateEnvelopeDto->method('getCurrentBudget')->willReturn('1500.00');
        $updateEnvelopeDto->method('getTitle')->willReturn('Test Title');

        $parentEnvelope = new Envelope();
        $parentEnvelope->setId(1);
        $parentEnvelope->setTargetBudget('1000.00');
        $parentEnvelope->setCurrentBudget('500.00');

        $envelope = new Envelope();
        $envelope->setId(2);
        $envelope->setCurrentBudget('500.00');
        $envelope->setParent($parentEnvelope);

        $this->targetBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1000.00', $parentEnvelope, $envelope);

        $this->currentBudgetValidator->expects($this->once())
            ->method('validate')
            ->with('1500.00', '1000.00', $parentEnvelope);

        $this->editEnvelopeBuilder->setUpdateEnvelopeDto($updateEnvelopeDto);
        $this->editEnvelopeBuilder->setParentEnvelope($parentEnvelope);
        $this->editEnvelopeBuilder->setEnvelope($envelope);

        $this->expectException(CurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->editEnvelopeBuilder->build();
    }
}
