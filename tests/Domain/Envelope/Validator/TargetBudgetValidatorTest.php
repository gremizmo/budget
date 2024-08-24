<?php

namespace App\Tests\Domain\Envelope\Validator;

use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Validator\TargetBudgetValidator;
use PHPUnit\Framework\TestCase;

class TargetBudgetValidatorTest extends TestCase
{
    private TargetBudgetValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new TargetBudgetValidator();
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function testValidateWithParentEnvelope(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection());

        $this->validator->validate('100.00', $parentEnvelope);

        $this->assertTrue(true);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function testValidateWithoutParentEnvelope(): void
    {
        $this->validator->validate('100.00', null);

        $this->assertTrue(true);
    }

    public function testValidateExceedsParentAvailableBudget(): void
    {
        $this->expectException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class);

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('150.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection());

        $this->validator->validate('100.00', $parentEnvelope);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function testValidateDoesNotExceedParentAvailableBudget(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection());

        $this->validator->validate('100.00', $parentEnvelope);

        $this->assertTrue(true);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function testValidateWithChildrenEnvelopes(): void
    {
        $childEnvelope = $this->createMock(EnvelopeInterface::class);
        $childEnvelope->method('getTargetBudget')->willReturn('50.00');

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope]));

        $this->validator->validate('100.00', $parentEnvelope);

        $this->assertTrue(true);
    }

    public function testValidateExceedsTotalChildrenTargetBudgets(): void
    {
        $this->expectException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class);

        $childEnvelope = $this->createMock(EnvelopeInterface::class);
        $childEnvelope->method('getTargetBudget')->willReturn('150.00');

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope]));

        $this->validator->validate('100.00', $parentEnvelope);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function testValidateWithNoChildrenEnvelopes(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection());

        $this->validator->validate('100.00', $parentEnvelope);

        $this->assertTrue(true);
    }

    public function testValidateTotalChildrenTargetBudgetExceedsParentTargetBudget(): void
    {
        $this->expectException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class);

        $childEnvelope1 = $this->createMock(EnvelopeInterface::class);
        $childEnvelope1->method('getTargetBudget')->willReturn('150.00');

        $childEnvelope2 = $this->createMock(EnvelopeInterface::class);
        $childEnvelope2->method('getTargetBudget')->willReturn('100.00');

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope1, $childEnvelope2]));

        $this->validator->validate('100.00', $parentEnvelope);
    }

    public function testValidateWithCurrentEnvelope(): void
    {
        $this->expectException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class);

        $childEnvelope1 = $this->createMock(EnvelopeInterface::class);
        $childEnvelope1->method('getTargetBudget')->willReturn('150.00');

        $childEnvelope2 = $this->createMock(EnvelopeInterface::class);
        $childEnvelope2->method('getTargetBudget')->willReturn('100.00');

        $currentEnvelope = $this->createMock(EnvelopeInterface::class);
        $currentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $currentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope1, $childEnvelope2]));

        $this->validator->validate('100.00', parentEnvelope: null, currentEnvelope: $currentEnvelope);
    }

    /**
     * @throws \ReflectionException
     */
    public function testCalculateTotalChildrenCurrentBudgetReturnsZero(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getChildren')->willReturn([]);

        $reflection = new \ReflectionClass(TargetBudgetValidator::class);
        $method = $reflection->getMethod('calculateTotalChildrenCurrentBudget');
        $method->setAccessible(true);

        $result = $method->invoke($this->validator, $parentEnvelope);

        $this->assertSame(0.00, $result);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function testValidateWithCurrentEnvelopeTargetBudgetGreaterThanNewTargetBudget(): void
    {
        $currentEnvelope = $this->createMock(EnvelopeInterface::class);
        $currentEnvelope->method('getTargetBudget')->willReturn('300.00');

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('500.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('100.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection());

        $this->validator->validate('200.00', $parentEnvelope, $currentEnvelope);

        $this->assertTrue(true);
    }
}
