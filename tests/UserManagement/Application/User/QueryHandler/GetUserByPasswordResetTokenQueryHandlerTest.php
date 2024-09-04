<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\User\QueryHandler;

use App\UserManagement\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Application\User\QueryHandler\GetUserByPasswordResetTokenQueryHandler;
use App\UserManagement\Application\User\QueryHandler\GetUserByPasswordResetTokenQueryHandlerException;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetUserByPasswordResetTokenQueryHandlerTest extends TestCase
{
    private UserQueryRepositoryInterface&MockObject $userQueryRepository;
    private LoggerInterface&MockObject $logger;
    private GetUserByPasswordResetTokenQueryHandler $handler;

    protected function setUp(): void
    {
        $this->userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new GetUserByPasswordResetTokenQueryHandler(
            $this->userQueryRepository,
            $this->logger
        );
    }

    /**
     * @throws GetUserByPasswordResetTokenQueryHandlerException
     */
    public function testGetUserByPasswordResetTokenSuccess(): void
    {
        $user = $this->createMock(UserInterface::class);
        $query = new GetUserByPasswordResetTokenQuery('valid-token');

        $this->userQueryRepository->method('findOneBy')->with([
            'passwordResetToken' => 'valid-token',
        ])->willReturn($user);

        $result = $this->handler->__invoke($query);

        $this->assertSame($user, $result);
    }

    /**
     * @throws GetUserByPasswordResetTokenQueryHandlerException
     */
    public function testGetUserByPasswordResetTokenExceptionDuringProcess(): void
    {
        $this->expectException(GetUserByPasswordResetTokenQueryHandlerException::class);

        $query = new GetUserByPasswordResetTokenQuery('valid-token');

        $this->userQueryRepository->method('findOneBy')->willThrowException(new \Exception('Database error'));

        $this->handler->__invoke($query);
    }

    /**
     * @throws GetUserByPasswordResetTokenQueryHandlerException
     */
    public function testGetUserByPasswordResetTokenNotFound(): void
    {
        $this->expectException(GetUserByPasswordResetTokenQueryHandlerException::class);

        $query = new GetUserByPasswordResetTokenQuery('invalid-token');

        $this->userQueryRepository->method('findOneBy')->with([
            'passwordResetToken' => 'invalid-token',
        ])->willReturn(null);

        $this->handler->__invoke($query);
    }
}
