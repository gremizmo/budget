<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\Handlers\CommandHandlers;

use App\SharedContext\EventStore\EventStoreInterface;
use App\SharedContext\Infrastructure\Persistence\Repositories\EventSourcedRepository;
use App\UserManagement\Application\Commands\UpdateUserLastnameCommand;
use App\UserManagement\Application\Handlers\CommandHandlers\UpdateUserLastnameCommandHandler;
use App\UserManagement\Domain\Events\UserCreatedEvent;
use App\UserManagement\Presentation\HTTP\DTOs\UpdateUserLastnameInput;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateUserLastnameCommandHandlerTest extends TestCase
{
    private EventStoreInterface&MockObject $eventStore;
    private EventSourcedRepository $eventSourcedRepository;
    private UpdateUserLastnameCommandHandler $handler;

    protected function setUp(): void
    {
        $this->eventStore = $this->createMock(EventStoreInterface::class);
        $this->eventSourcedRepository = new EventSourcedRepository($this->eventStore);
        $this->handler = new UpdateUserLastnameCommandHandler(
            $this->eventSourcedRepository,
        );
    }

    public function testUpdateUserLastnameSuccess(): void
    {
        $createUserInput = new UpdateUserLastnameInput('Snow');
        $command = new UpdateUserLastnameCommand('7ac32191-3fa0-4477-8eb2-8dd3b0b7c836', $createUserInput->getLastname());

        $this->eventStore->expects($this->once())->method('load')->willReturn(
            [
                [
                    'aggregate_id' => '7ac32191-3fa0-4477-8eb2-8dd3b0b7c836',
                    'type' => UserCreatedEvent::class,
                    'occured_on' => '2020-10-10T12:00:00Z',
                    'payload' => json_encode([
                        'email' => 'test@gmail.com',
                        'roles' => ['ROLE_USER'],
                        'lastname' => 'Doe',
                        'password' => 'test',
                        'firstname' => 'David',
                        'occurredOn' => '2024-12-13T00:26:48+00:00',
                        'aggregateId' => '7ac32191-3fa0-4477-8eb2-8dd3b0b7c836',
                        'isConsentGiven' => true,
                    ]),
                ],
            ],
        );

        $this->eventStore->expects($this->once())->method('save');

        $this->handler->__invoke($command);
    }
}
