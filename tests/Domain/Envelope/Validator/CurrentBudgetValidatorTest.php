<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Validator;

use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Validator\EditEnvelopeCurrentBudgetValidator;
use PHPUnit\Framework\TestCase;

class CurrentBudgetValidatorTest extends TestCase
{
    private EditEnvelopeCurrentBudgetValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new EditEnvelopeCurrentBudgetValidator();
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     */
    public function testValidateCurrentBudgetExceedsTargetBudget(): void
    {
        $this->expectException(EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException::class);

        $this->validator->validate('300.00', '200.00', null);
    }

    /**
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function testValidateCurrentBudgetExceedsParentTargetBudget(): void
    {
        $this->expectException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $parentEnvelope->method('getTargetBudget')->willReturn('200.00');

        $childEnvelope = $this->createMock(EnvelopeInterface::class);
        $childEnvelope->method('getCurrentBudget')->willReturn('300.00');

        $currentEnvelope = $this->createMock(EnvelopeInterface::class);
        $currentEnvelope->method('getChildren')->willReturn(new EnvelopeCollection([$childEnvelope]));

        $this->validator->validate('300.00', '300.00', $parentEnvelope, $currentEnvelope);
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException
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
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException
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
