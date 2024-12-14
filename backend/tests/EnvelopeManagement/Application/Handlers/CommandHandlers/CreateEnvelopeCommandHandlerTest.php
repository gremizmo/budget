<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Application\Handlers\CommandHandlers;

use App\EnvelopeManagement\Application\Commands\CreateEnvelopeCommand;
use App\EnvelopeManagement\Application\Handlers\CommandHandlers\CreateEnvelopeCommandHandler;
use App\EnvelopeManagement\Domain\Events\EnvelopeCreatedEvent;
use App\EnvelopeManagement\Domain\Exceptions\EnvelopeAlreadyExistsException;
use App\EnvelopeManagement\Domain\Exceptions\EnvelopeNameAlreadyExistsForUserException;
use App\EnvelopeManagement\Domain\Exceptions\TargetBudgetException;
use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\Presentation\HTTP\DTOs\CreateEnvelopeInput;
use App\EnvelopeManagement\ReadModels\Views\EnvelopeView;
use App\SharedContext\EventStore\EventStoreInterface;
use App\SharedContext\Infrastructure\Persistence\Repositories\EventSourcedRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeCommandHandlerTest extends TestCase
{
    private CreateEnvelopeCommandHandler $createEnvelopeCommandHandler;

    private EventStoreInterface&MockObject $eventStore;
    private EventSourcedRepository $eventSourcedRepository;
    private EnvelopeRepositoryInterface&MockObject $envelopeRepository;

    #[\Override]
    protected function setUp(): void
    {
        $this->eventStore = $this->createMock(EventStoreInterface::class);
        $this->envelopeRepository = $this->createMock(EnvelopeRepositoryInterface::class);
        $this->eventSourcedRepository = new EventSourcedRepository($this->eventStore);

        $this->createEnvelopeCommandHandler = new CreateEnvelopeCommandHandler(
            $this->eventSourcedRepository,
            $this->envelopeRepository,
        );
    }

    public function testCreateEnvelopeSuccess(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput(
            '0099c0ce-3b53-4318-ba7b-994e437a859b',
            'test name',
            '200.00'
        );
        $createEnvelopeCommand = new CreateEnvelopeCommand(
            $createEnvelopeInput->getUuid(),
            'd26cc02e-99e7-428c-9d61-572dff3f84a7',
            $createEnvelopeInput->getName(),
            $createEnvelopeInput->getTargetBudget(),
        );

        $this->eventStore->expects($this->once())->method('load')->willThrowException(new \RuntimeException());
        $this->eventStore->expects($this->once())->method('save');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testCreateEnvelopeWithNegativeTargetBudgetFailure(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput(
            '0099c0ce-3b53-4318-ba7b-994e437a859b',
            'test name',
            '-200.00'
        );
        $createEnvelopeCommand = new CreateEnvelopeCommand(
            $createEnvelopeInput->getUuid(),
            'd26cc02e-99e7-428c-9d61-572dff3f84a7',
            $createEnvelopeInput->getName(),
            $createEnvelopeInput->getTargetBudget(),
        );

        $this->eventStore->expects($this->once())->method('load')->willThrowException(new \RuntimeException());
        $this->eventStore->expects($this->never())->method('save');

        $this->expectException(TargetBudgetException::class);
        $this->expectExceptionMessage('Target budget must be greater than 0.');

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testCreateEnvelopeWithNameDoubloonFailure(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput(
            '0099c0ce-3b53-4318-ba7b-994e437a859b',
            'test name',
            '-200.00'
        );
        $createEnvelopeCommand = new CreateEnvelopeCommand(
            $createEnvelopeInput->getUuid(),
            'd26cc02e-99e7-428c-9d61-572dff3f84a7',
            $createEnvelopeInput->getName(),
            $createEnvelopeInput->getTargetBudget(),
        );

        $envelopeView = EnvelopeView::createFromRepository(
            [
                'uuid' => 'be0c3a86-c3c9-467f-b675-3f519fd96111',
                'name' => 'another envelope name',
                'target_budget' => '300.00',
                'current_budget' => '150.00',
                'user_uuid' => 'd26cc02e-99e7-428c-9d61-572dff3f84a7',
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'is_deleted' => false,
            ]
        );

        $this->eventStore->expects($this->once())->method('load')->willThrowException(new \RuntimeException());
        $this->envelopeRepository->expects($this->once())->method('findOneBy')->willReturn($envelopeView);
        $this->eventStore->expects($this->never())->method('save');

        $this->expectException(EnvelopeNameAlreadyExistsForUserException::class);
        $this->expectExceptionMessage(EnvelopeNameAlreadyExistsForUserException::MESSAGE);

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }

    public function testCreateEnvelopeWithSameUuidFailure(): void
    {
        $createEnvelopeInput = new CreateEnvelopeInput(
            '0099c0ce-3b53-4318-ba7b-994e437a859b',
            'test name',
            '-200.00'
        );
        $createEnvelopeCommand = new CreateEnvelopeCommand(
            $createEnvelopeInput->getUuid(),
            'd26cc02e-99e7-428c-9d61-572dff3f84a7',
            $createEnvelopeInput->getName(),
            $createEnvelopeInput->getTargetBudget(),
        );

        $this->eventStore->expects($this->once())->method('load')->willReturn(
            [
                [
                    'aggregate_id' => $createEnvelopeInput->getUuid(),
                    'type' => EnvelopeCreatedEvent::class,
                    'occured_on' => '2020-10-10T12:00:00Z',
                    'payload' => json_encode([
                        'name' => 'test1',
                        'userId' => 'a871e446-ddcd-4e7a-9bf9-525bab84e566',
                        'occurredOn' => '2024-12-07T22:03:35+00:00',
                        'aggregateId' => $createEnvelopeInput->getUuid(),
                        'targetBudget' => '20.00',
                    ]),
                ],
            ],
        );
        $this->eventStore->expects($this->never())->method('save');

        $this->expectException(EnvelopeAlreadyExistsException::class);
        $this->expectExceptionMessage(EnvelopeAlreadyExistsException::MESSAGE);

        $this->createEnvelopeCommandHandler->__invoke($createEnvelopeCommand);
    }
}
