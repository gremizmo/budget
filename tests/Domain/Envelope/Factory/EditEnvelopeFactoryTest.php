<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\EditEnvelopeBuilder;
use App\Domain\Envelope\Dto\EditEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\SelfParentEnvelopeException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Factory\EditEnvelopeFactory;
use App\Domain\Envelope\Validator\CurrentBudgetValidator;
use App\Domain\Envelope\Validator\TargetBudgetValidator;
use App\Domain\Envelope\Validator\TitleValidator;
use App\Domain\Shared\Adapter\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EditEnvelopeFactoryTest extends TestCase
{
    private LoggerInterface&MockObject $logger;
    private EditEnvelopeBuilder $editEnvelopeBuilder;
    private EditEnvelopeFactory $editEnvelopeFactory;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $targetBudgetValidator = new TargetBudgetValidator();
        $currentBudgetValidator = new CurrentBudgetValidator();
        $this->editEnvelopeBuilder = new EditEnvelopeBuilder($targetBudgetValidator, $currentBudgetValidator, $this->createMock(TitleValidator::class));
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
        $editEnvelopeDto = new EditEnvelopeDto('Test Title', '50.00', '100.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());
        $parentEnvelope->setId(1);
        $envelope = new Envelope();
        $envelope->setParent($parentEnvelope);
        $envelope->setId(2);

        $result = $this->editEnvelopeFactory->createFromDto($envelope, $editEnvelopeDto, $parentEnvelope);

        $this->assertInstanceOf(Envelope::class, $result);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testCreateFromDtoFailureDueToChildrenTargetBudgetsExceedsParent(): void
    {
        $editEnvelopeDto = new EditEnvelopeDto('Test Title', '50.00', '300.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());
        $parentEnvelope->setId(1);
        $envelope = new Envelope();
        $envelope->setParent($parentEnvelope);
        $envelope->setId(2);

        $this->expectException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class);

        $this->editEnvelopeFactory->createFromDto($envelope, $editEnvelopeDto, $parentEnvelope);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testCreateFromDtoFailureDueToParentCurrentBudgetExceedsTarget(): void
    {
        $editEnvelopeDto = new EditEnvelopeDto('Test Title', '250.00', '100.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setId(1);
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());
        $envelope = new Envelope();
        $envelope->setParent($parentEnvelope);
        $envelope->setId(2);

        $this->expectException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->editEnvelopeFactory->createFromDto($envelope, $editEnvelopeDto, $parentEnvelope);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testHandleParentChange(): void
    {
        $editEnvelopeDto = new EditEnvelopeDto('Test Title', '150.00', '100.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setId(1);
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setCurrentBudget('150.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());

        $envelope = new Envelope();
        $envelope->setId(2);
        $envelope->setParent($parentEnvelope);
        $envelope->setCurrentBudget('150.00');
        $envelope->setTargetBudget('150.00');

        $newParentEnvelope = new Envelope();
        $newParentEnvelope->setId(3);
        $newParentEnvelope->setTargetBudget('300.00');
        $newParentEnvelope->setCurrentBudget('100.00');
        $newParentEnvelope->setChildren(new EnvelopeCollection());

        $this->editEnvelopeFactory->createFromDto($envelope, $editEnvelopeDto, $newParentEnvelope);

        $this->assertEquals('0.00', $parentEnvelope->getCurrentBudget());
        $this->assertEquals('250.00', $newParentEnvelope->getCurrentBudget());
    }
}
