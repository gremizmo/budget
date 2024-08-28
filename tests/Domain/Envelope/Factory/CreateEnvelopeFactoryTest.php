<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\Domain\Envelope\Dto\CreateEnvelopeDto;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Exception\Factory\CreateEnvelopeFactoryException;
use App\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\Domain\Envelope\Validator\EditEnvelopeCurrentBudgetValidator;
use App\Domain\Envelope\Validator\EditEnvelopeTargetBudgetValidator;
use App\Domain\Envelope\Validator\EditEnvelopeTitleValidator;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Infra\Http\Rest\Envelope\Entity\Envelope;
use App\Infra\Http\Rest\User\Entity\User;
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
        $targetBudgetValidator = new EditEnvelopeTargetBudgetValidator();
        $currentBudgetValidator = new EditEnvelopeCurrentBudgetValidator();
        $this->createEnvelopeBuilder = new CreateEnvelopeBuilder($targetBudgetValidator, $currentBudgetValidator, $this->createMock(EditEnvelopeTitleValidator::class));
        $this->createEnvelopeFactory = new CreateEnvelopeFactory(
            $this->logger,
            $this->createEnvelopeBuilder
        );
    }

    /**
     * @throws CreateEnvelopeFactoryException
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
     * @throws CreateEnvelopeFactoryException
     */
    public function testCreateFromDtoFailureDueToChildrenTargetBudgetsExceedsParent(): void
    {
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '50.00', '300.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());
        $user = new User();

        $this->expectException(CreateEnvelopeFactoryException::class);

        $this->createEnvelopeFactory->createFromDto($createEnvelopeDto, $parentEnvelope, $user);
    }

    /**
     * @throws CreateEnvelopeFactoryException
     */
    public function testCreateFromDtoFailureDueToCurrentBudgetExceedsTarget(): void
    {
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '250.00', '100.00');
        $parentEnvelope = new Envelope();
        $parentEnvelope->setTargetBudget('200.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());

        $user = new User();

        $this->expectException(CreateEnvelopeFactoryException::class);

        $this->createEnvelopeFactory->createFromDto($createEnvelopeDto, $parentEnvelope, $user);
    }
}
