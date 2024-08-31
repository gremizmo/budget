<?php

declare(strict_types=1);

namespace App\Tests\Application\User\QueryHandler;

use App\Domain\Shared\Model\UserInterface;
use App\UserManagement\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Application\User\QueryHandler\GetUserByPasswordResetTokenQueryHandler;
use App\UserManagement\Application\User\QueryHandler\UserNotFoundException;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetUserByPasswordResetTokenQueryHandlerTest extends TestCase
{
    public function testInvokeReturnsUser(): void
    {
        $userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);
        $user = $this->createMock(UserInterface::class);
        $token = 'reset-token';
        $query = new GetUserByPasswordResetTokenQuery($token);
        $handler = new GetUserByPasswordResetTokenQueryHandler($userQueryRepository);

        $userQueryRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['passwordResetToken' => $token])
            ->willReturn($user);

        $result = $handler($query);

        $this->assertSame($user, $result);
    }

    public function testInvokeThrowsUserNotFoundException(): void
    {
        $userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);
        $token = 'reset-token';
        $query = new GetUserByPasswordResetTokenQuery($token);
        $handler = new GetUserByPasswordResetTokenQueryHandler($userQueryRepository);

        $userQueryRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['passwordResetToken' => $token])
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found with password reset token');

        $handler($query);
    }
}
