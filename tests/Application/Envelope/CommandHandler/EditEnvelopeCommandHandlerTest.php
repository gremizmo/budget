<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\EditEnvelopeCommand;
use App\Application\Envelope\CommandHandler\EditEnvelopeCommandHandler;
use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Factory\EditEnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EditEnvelopeCommandHandlerTest extends TestCase
{
    private MockObject&EnvelopeCommandRepositoryInterface $envelopeRepositoryMock;
    private MockObject&EditEnvelopeFactoryInterface $editEnvelopeFactoryMock;
    private EditEnvelopeCommandHandler $editEnvelopeCommandHandler;

    protected function setUp(): void
    {
        $this->envelopeRepositoryMock = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->editEnvelopeFactoryMock = $this->createMock(EditEnvelopeFactoryInterface::class);
        $this->editEnvelopeCommandHandler = new EditEnvelopeCommandHandler(
            $this->envelopeRepositoryMock,
            $this->editEnvelopeFactoryMock
        );
    }

    /**
     * @dataProvider envelopeDataProvider
     */
    public function testInvokeSuccess(EditEnvelopeCommand $editEnvelopeCommand): void
    {
        $envelope = $this->createMock(EnvelopeInterface::class);

        $this->editEnvelopeFactoryMock->expects($this->once())
            ->method('createFromDto')
            ->with(
                $editEnvelopeCommand->getEnvelope(),
                $editEnvelopeCommand->getUpdateEnvelopeDTO(),
                $editEnvelopeCommand->getParentEnvelope()
            )
            ->willReturn($envelope);

        $this->envelopeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($envelope);

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    public function testInvokeFailure(): void
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $envelope = $this->createMock(EnvelopeInterface::class);
        $updateEnvelopeDto = $this->createMock(UpdateEnvelopeDtoInterface::class);

        $editEnvelopeCommand = new EditEnvelopeCommand(
            $envelope,
            $updateEnvelopeDto,
            $parentEnvelope
        );

        $exception = new ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);

        $this->editEnvelopeFactoryMock->expects($this->once())
            ->method('createFromDto')
            ->with(
                $editEnvelopeCommand->getEnvelope(),
                $editEnvelopeCommand->getUpdateEnvelopeDTO(),
                $editEnvelopeCommand->getParentEnvelope()
            )
            ->willThrowException($exception);

        $this->expectException(ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException::class);

        $this->editEnvelopeCommandHandler->__invoke($editEnvelopeCommand);
    }

    public function envelopeDataProvider(): array
    {
        $parentEnvelope = $this->createMock(EnvelopeInterface::class);
        $envelope = $this->createMock(EnvelopeInterface::class);
        $updateEnvelopeDto = $this->createMock(UpdateEnvelopeDtoInterface::class);

        return [
            'with parent' => [
                new EditEnvelopeCommand(
                    $envelope,
                    $updateEnvelopeDto,
                    $parentEnvelope
                ),
            ],
            'without parent' => [
                new EditEnvelopeCommand(
                    $envelope,
                    $updateEnvelopeDto,
                    null
                ),
            ],
        ];
    }
}
