<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Application\User\QueryHandler;

use App\UserManagement\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Application\User\QueryHandler\GetUserByPasswordResetTokenQueryHandler;
use App\UserManagement\Application\User\QueryHandler\UserNotFoundException;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetUserByPasswordResetTokenQueryHandlerTest extends TestCase
{
    private UserQueryRepositoryInterface&MockObject $userQueryRepository;
    private GetUserByPasswordResetTokenQueryHandler $handler;

    protected function setUp(): void
    {
        $this->userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);
        $this->handler = new GetUserByPasswordResetTokenQueryHandler(
            $this->userQueryRepository,
        );
    }

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

    public function testGetUserByPasswordResetTokenNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $query = new GetUserByPasswordResetTokenQuery('invalid-token');

        $this->userQueryRepository->method('findOneBy')->with([
            'passwordResetToken' => 'invalid-token',
        ])->willReturn(null);

        $this->handler->__invoke($query);
    }
}
