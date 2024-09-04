<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\User\QueryHandler;

use App\UserManagement\Application\User\Query\GetUserAlreadyExistsQuery;
use App\UserManagement\Application\User\QueryHandler\GetUserAlreadyExistsQueryHandler;
use App\UserManagement\Application\User\QueryHandler\GetUserAlreadyExistsQueryHandlerException;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetUserAlreadyExistsQueryHandlerTest extends TestCase
{
    private UserQueryRepositoryInterface&MockObject $userQueryRepository;
    private LoggerInterface&MockObject $logger;
    private GetUserAlreadyExistsQueryHandler $handler;

    protected function setUp(): void
    {
        $this->userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new GetUserAlreadyExistsQueryHandler(
            $this->userQueryRepository,
            $this->logger
        );
    }

    /**
     * @throws GetUserAlreadyExistsQueryHandlerException
     */
    public function testGetUserAlreadyExistsSuccess(): void
    {
        $user = $this->createMock(UserInterface::class);
        $query = new GetUserAlreadyExistsQuery('test@example.com');

        $this->userQueryRepository->method('findOneBy')->with([
            'email' => 'test@example.com',
        ])->willReturn($user);

        $result = $this->handler->__invoke($query);

        $this->assertSame($user, $result);
    }

    /**
     * @throws GetUserAlreadyExistsQueryHandlerException
     */
    public function testGetUserAlreadyExistsExceptionDuringProcess(): void
    {
        $this->expectException(GetUserAlreadyExistsQueryHandlerException::class);

        $query = new GetUserAlreadyExistsQuery('test@example.com');

        $this->userQueryRepository->method('findOneBy')->willThrowException(new \Exception('Database error'));

        $this->handler->__invoke($query);
    }

    /**
     * @throws GetUserAlreadyExistsQueryHandlerException
     */
    public function testGetUserAlreadyExistsNotFound(): void
    {
        $query = new GetUserAlreadyExistsQuery('test@example.com');

        $this->userQueryRepository->method('findOneBy')->with([
            'email' => 'test@example.com',
        ])->willReturn(null);

        $result = $this->handler->__invoke($query);

        $this->assertNull($result);
    }
}
