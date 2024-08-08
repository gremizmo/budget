<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\Domain\Envelope\Dto\CreateEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\SelfParentEnvelopeException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\Domain\Envelope\Validator\CurrentBudgetValidator;
use App\Domain\Envelope\Validator\TargetBudgetValidator;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeFactoryTest extends TestCase
{
    private LoggerInterface&MockObject $logger;
    private CreateEnvelopeBuilder $createEnvelopeBuilder;
    private CreateEnvelopeFactory $createEnvelopeFactory;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $targetBudgetValidator = new TargetBudgetValidator();
        $currentBudgetValidator = new CurrentBudgetValidator();
        $this->createEnvelopeBuilder = new CreateEnvelopeBuilder($targetBudgetValidator, $currentBudgetValidator);
        $this->createEnvelopeFactory = new CreateEnvelopeFactory(
            $this->logger,
            $this->createEnvelopeBuilder
        );
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testCreateFromDtoSuccess(): void
    {
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '50.00', '100.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());
        $user = new User();

        $result = $this->createEnvelopeFactory->createFromDto($createEnvelopeDto, $parentEnvelope, $user);

        $this->assertInstanceOf(Envelope::class, $result);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testCreateFromDtoFailureDueToChildrenTargetBudgetsExceedsParent(): void
    {
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '50.00', '300.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());
        $user = new User();

        $this->expectException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class);

        $this->createEnvelopeFactory->createFromDto($createEnvelopeDto, $parentEnvelope, $user);
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function testCreateFromDtoFailureDueToParentCurrentBudgetExceedsTarget(): void
    {
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '250.00', '100.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());

        $user = new User();

        $this->expectException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->createEnvelopeFactory->createFromDto($createEnvelopeDto, $parentEnvelope, $user);
    }
}
