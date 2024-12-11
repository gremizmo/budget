<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\QueryHandler;

use App\UserManagement\Application\Query\ShowUserQuery;
use App\UserManagement\Application\QueryHandler\ShowUserQueryHandler;
use App\UserManagement\Application\QueryHandler\UserNotFoundException;
use App\UserManagement\Domain\Model\UserInterface;
use App\UserManagement\Domain\Repository\UserQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShowUserQueryHandlerTest extends TestCase
{
    private UserQueryRepositoryInterface&MockObject $userQueryRepository;
    private ShowUserQueryHandler $handler;

    protected function setUp(): void
    {
        $this->userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);
        $this->handler = new ShowUserQueryHandler($this->userQueryRepository);
    }

    public function testShowUserSuccess(): void
    {
        $user = $this->createMock(UserInterface::class);
        $query = new ShowUserQuery('test@example.com');

        $this->userQueryRepository->method('findOneBy')->with([
            'email' => 'test@example.com',
        ])->willReturn($user);

        $result = $this->handler->__invoke($query);

        $this->assertSame($user, $result);
    }

    public function testShowUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $query = new ShowUserQuery('test@example.com');

        $this->userQueryRepository->method('findOneBy')->with([
            'email' => 'test@example.com',
        ])->willReturn(null);

        $this->handler->__invoke($query);
    }
}
