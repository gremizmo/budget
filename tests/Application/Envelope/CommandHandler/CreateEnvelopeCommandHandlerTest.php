<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\CommandHandler;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Application\Envelope\CommandHandler\CreateEnvelopeCommandHandler;
use App\Domain\Envelope\Dto\CreateEnvelopeDto;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\CommandHandler\CreateEnvelopeCommandHandlerException;
use App\Domain\Envelope\Factory\CreateEnvelopeFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeCommandHandlerTest extends TestCase
{
    private MockObject&EnvelopeCommandRepositoryInterface $envelopeRepositoryMock;
    private MockObject&CreateEnvelopeFactoryInterface $createEnvelopeFactoryMock;
    private MockObject&UserInterface $userMock;
    private CreateEnvelopeCommandHandler $createEnvelopeCommandHandler;

    protected function setUp(): void
    {
        $this->envelopeRepositoryMock = $this->createMock(EnvelopeCommandRepositoryInterface::class);
        $this->createEnvelopeFactoryMock = $this->createMock(CreateEnvelopeFactoryInterface::class);
        $this->createEnvelopeCommandHandler = new CreateEnvelopeCommandHandler(
            $this->envelopeRepositoryMock,
            $this->createEnvelopeFactoryMock
        );
        $this->userMock = $this->createMock(UserInterface::class);
    }

    /**
     * @dataProvider envelopeDataProvider
     */
    public function testInvokeSuccess(CreateEnvelopeCommand $createEnvelopeCommand): void
    {
        $envelope = $this->createMock(EnvelopeInterface::class);

        $this->createEnvelopeFactoryMock->expects($this->once())
            ->method('createFromDto')
            ->with(
                $createEnvelopeCommand->getCreateEnvelopeDto(),
                $createEnvelopeCommand->getParentEnvelope(),
                $createEnvelopeCommand->getUser()
            )
            ->willReturn($envelope);

        $this->envelopeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($envelope);

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
            $this->userMock,
            $parentEnvelope,
        );

        $exception = new CreateEnvelopeCommandHandlerException(CreateEnvelopeCommandHandlerException::MESSAGE, 400);

        $this->createEnvelopeFactoryMock->expects($this->once())
            ->method('createFromDto')
            ->with(
                $createEnvelopeCommand->getCreateEnvelopeDto(),
                $createEnvelopeCommand->getParentEnvelope(),
                $createEnvelopeCommand->getUser()
            )
            ->willThrowException($exception);

        $this->expectException(CreateEnvelopeCommandHandlerException::class);

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    /**
     * @return array<mixed>
     */
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
                    new User(),
                    $parentEnvelope,
                ),
            ],
            'without parent' => [
                new CreateEnvelopeCommand(
                    new CreateEnvelopeDto('Test', '1000.00', '2000.00'),
                    new User(),
                    null,
                ),
            ],
        ];
    }
}
