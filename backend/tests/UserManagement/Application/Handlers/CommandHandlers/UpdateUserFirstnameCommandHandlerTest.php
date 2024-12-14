<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\Handlers\CommandHandlers;

use App\SharedContext\EventStore\EventStoreInterface;
use App\SharedContext\Infrastructure\Persistence\Repositories\EventSourcedRepository;
use App\UserManagement\Application\Commands\UpdateUserFirstnameCommand;
use App\UserManagement\Application\Handlers\CommandHandlers\UpdateUserFirstnameCommandHandler;
use App\UserManagement\Domain\Events\UserCreatedEvent;
use App\UserManagement\Presentation\HTTP\DTOs\UpdateUserFirstnameInput;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateUserFirstnameCommandHandlerTest extends TestCase
{
    private EventStoreInterface&MockObject $eventStore;
    private EventSourcedRepository $eventSourcedRepository;
    private UpdateUserFirstnameCommandHandler $handler;

    #[\Override]
    protected function setUp(): void
    {
        $this->eventStore = $this->createMock(EventStoreInterface::class);
        $this->eventSourcedRepository = new EventSourcedRepository($this->eventStore);
        $this->handler = new UpdateUserFirstnameCommandHandler(
            $this->eventSourcedRepository,
        );
    }

    public function testUpdateUserFirstnameSuccess(): void
    {
        $createUserInput = new UpdateUserFirstnameInput('John');
        $command = new UpdateUserFirstnameCommand('7ac32191-3fa0-4477-8eb2-8dd3b0b7c836', $createUserInput->getFirstname());

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
