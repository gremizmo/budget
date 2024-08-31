<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Validator;

use App\BudgetManagement\Domain\Envelope\Exception\CurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\BudgetManagement\Domain\Envelope\Exception\CurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\BudgetManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\BudgetManagement\Domain\Envelope\Validator\EditEnvelopeCurrentBudgetValidator;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Exception\ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException;
use PHPUnit\Framework\TestCase;

class CurrentBudgetValidatorTest extends TestCase
{
    private EditEnvelopeCurrentBudgetValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new EditEnvelopeCurrentBudgetValidator();
    }

    /**
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     */
    public function testValidateCurrentBudgetExceedsTargetBudget(): void
    {
        $this->expectException(CurrentBudgetExceedsEnvelopeTargetBudgetException::class);

        $this->validator->validate('300.00', '200.00', null);
    }

    /**
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws CurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function testValidateCurrentBudgetExceedsParentTargetBudget(): void
    {
        $this->expectException(CurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');

        $childEnvelope = $this->createMock(EnvelopeInterface::class);
        $childEnvelope->method('getCurrentBudget')->willReturn('300.00');

        $currentEnvelope = $this->createMock(EnvelopeInterface::class);
        $currentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope]));

        $this->validator->validate('300.00', '300.00', $parentEnvelope, $currentEnvelope);
    }

    /**
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws CurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function testValidateCurrentBudgetLessThanTotalChildrenCurrentBudget(): void
    {
        $this->expectException(ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException::class);

        $childEnvelope1 = $this->createMock(EnvelopeInterface::class);
        $childEnvelope1->method('getCurrentBudget')->willReturn('150.00');

        $childEnvelope2 = $this->createMock(EnvelopeInterface::class);
        $childEnvelope2->method('getCurrentBudget')->willReturn('100.00');

        $currentEnvelope = $this->createMock(EnvelopeInterface::class);
        $currentEnvelope->method('getCurrentBudget')->willReturn('200.00');
        $currentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope1, $childEnvelope2]));

        $this->validator->validate('200.00', '300.00', null, $currentEnvelope);
    }

    /**
     * @throws CurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws CurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function testValidateValidBudgets(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('300.00');

        $childEnvelope = $this->createMock(EnvelopeInterface::class);
        $childEnvelope->method('getCurrentBudget')->willReturn('50.00');

        $currentEnvelope = $this->createMock(EnvelopeInterface::class);
        $currentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope]));

        $this->validator->validate('200.00', '250.00', $parentEnvelope, $currentEnvelope);

        $this->assertTrue(true);
    }
}
