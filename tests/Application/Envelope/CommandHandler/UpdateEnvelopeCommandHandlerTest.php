<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\EditEnvelopeCommand;
use App\Application\Envelope\CommandHandler\EditEnvelopeCommandHandler;
use App\Domain\Envelope\Dto\EditEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Exception\EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Factory\CreateEnvelopeFactory;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\Shared\Adapter\UuidGeneratorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateEnvelopeCommandHandlerTest extends TestCase
{
    private MockObject&EnvelopeCommandRepositoryInterface $envelopeRepositoryMock;
    private MockObject&LoggerInterface $loggerMock;
    private EditEnvelopeCommandHandler $updateEnvelopeCommandHandler;

    protected function setUp(): void
    {
        $this->envelopeRepositoryMock = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->updateEnvelopeCommandHandler = new EditEnvelopeCommandHandler(
            $this->envelopeRepositoryMock,
            new CreateEnvelopeFactory($this->createMock(UuidGeneratorInterface::class)),
            $this->loggerMock
        );
    }

    /**
     * @dataProvider envelopeDataProvider
     */
    public function testInvokeSuccess(EditEnvelopeCommand $updateEnvelopeCommand): void
    {
        $this->envelopeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Envelope::class));

        $this->updateEnvelopeCommandHandler->__invoke($updateEnvelopeCommand);
    }

    public function testInvokeFailure(): void
    {
        $parentEnvelope = new Envelope();
        $parentEnvelope->setId(1);
        $parentEnvelope->setTargetBudget('1000.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());

        $envelope = new Envelope();

        $updateEnvelopeDto = new EditEnvelopeDto('Updated Title', '150.0', '3000.00', 1);
        $updateEnvelopeCommand = new EditEnvelopeCommand($envelope, $updateEnvelopeDto, $parentEnvelope);

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with(
                EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE,
                [
                    'parentEnvelope' => 1,
                    'parentEnvelopeTargetBudget' => '1000.00',
                    'currentEnvelopeTargetBudget' => '3000.00',
                ]
            );

        $this->expectException(EnvelopeTargetBudgetExceedsParentEnvelopeTargetBudgetException::class);

        $this->updateEnvelopeCommandHandler->__invoke($updateEnvelopeCommand);
    }

    public function envelopeDataProvider(): array
    {
        $parentEnvelope = new Envelope();
        $parentEnvelope->setId(1);
        $parentEnvelope->setTargetBudget('3000.00');
        $parentEnvelope->setChildren(new EnvelopeCollection());

        return [
            'with parent' => [
                new EditEnvelopeCommand(
                    new Envelope(),
                    new EditEnvelopeDto('Updated Title', '150.0', '250.0', 1),
                    $parentEnvelope,
                ),
            ],
            'without parent' => [
                new EditEnvelopeCommand(
                    new Envelope(),
                    new EditEnvelopeDto('Updated Title', '150.0', '250.0', 1),
                    null,
                ),
            ],
        ];
    }
}
