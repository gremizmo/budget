<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Application\Envelope\CommandHandler\CreateEnvelopeCommandHandler;
use App\Domain\Envelope\Dto\CreateEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Exception\EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\Shared\Adapter\UuidGeneratorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeCommandHandlerTest extends TestCase
{
    private MockObject&EnvelopeCommandRepositoryInterface $envelopeRepositoryMock;
    private MockObject&LoggerInterface $loggerMock;
    private CreateEnvelopeCommandHandler $createEnvelopeCommandHandler;

    protected function setUp(): void
    {
        $this->envelopeRepositoryMock = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->createEnvelopeCommandHandler = new CreateEnvelopeCommandHandler(
            $this->envelopeRepositoryMock,
            new CreateEnvelopeFactory($this->createMock(UuidGeneratorInterface::class)),
            $this->loggerMock
        );
    }

    /**
     * @dataProvider envelopeDataProvider
     */
    public function testInvokeSuccess(CreateEnvelopeCommand $createEnvelopeCommand): void
    {
        $this->envelopeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Envelope::class));

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testInvokeFailure(): void
    {
        $parentEnvelope = new Envelope();
        $parentEnvelope->setId(1);
        $parentEnvelope->setTargetBudget('1000.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());

        $createEnvelopeCommand = new CreateEnvelopeCommand(
            new CreateEnvelopeDto('Test', '1000.00', '2000.00'),
            $parentEnvelope
        );

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with(
                EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
                [
                    'parentEnvelope' => 1,
                    'parentEnvelopeTargetBudget' => '1000.00',
                    'currentEnvelopeTargetBudget' => '2000.00',
                ]
            );

        $this->expectException(EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function envelopeDataProvider(): array
    {
        $parentEnvelope = new Envelope();
        $parentEnvelope->setId(1);
        $parentEnvelope->setTargetBudget('3000.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());

        return [
            'with parent' => [
                new CreateEnvelopeCommand(
                    new CreateEnvelopeDto('Test', '1000.00', '2000.00'),
                    $parentEnvelope
                ),
            ],
            'without parent' => [
                new CreateEnvelopeCommand(
                    new CreateEnvelopeDto('Test', '1000.00', '2000.00'),
                    null
                ),
            ],
        ];
    }
}
