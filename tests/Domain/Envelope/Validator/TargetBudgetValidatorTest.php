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

    public function testValidateWithParentEnvelope(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection());

        $this->validator->validate('100.00', $parentEnvelope);

        $this->assertTrue(true); // No exception means the test passed
    }

    public function testValidateWithoutParentEnvelope(): void
    {
        $this->validator->validate('100.00', null);

        $this->assertTrue(true); // No exception means the test passed
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

    public function testValidateDoesNotExceedParentAvailableBudget(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection());

        $this->validator->validate('100.00', $parentEnvelope);

        $this->assertTrue(true); // No exception means the test passed
    }

    public function testValidateWithChildrenEnvelopes(): void
    {
        $childEnvelope = $this->createMock(EnvelopeInterface::class);
        $childEnvelope->method('getTargetBudget')->willReturn('50.00');

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope]));

        $this->validator->validate('100.00', $parentEnvelope);

        $this->assertTrue(true); // No exception means the test passed
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

    public function testValidateWithNoChildrenEnvelopes(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');
        $parentEnvelope->method('getCurrentBudget')->willReturn('50.00');
        $parentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection());

        $this->validator->validate('100.00', $parentEnvelope);

        $this->assertTrue(true); // No exception means the test passed
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
}
