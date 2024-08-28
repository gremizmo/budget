<?php

declare(strict_types=1);

namespace App\Tests\Application\User\QueryHandler;

use App\Application\User\Query\ShowUserQuery;
use App\Application\User\QueryHandler\ShowUserQueryHandler;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserQueryRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ShowUserQueryHandlerTest extends TestCase
{
    public function testInvokeUserFound(): void
    {
        $userEmail = 'test@example.com';
        $query = new ShowUserQuery($userEmail);

        $user = $this->createMock(UserInterface::class);
        $userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $userQueryRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $userEmail])
            ->willReturn($user);

        $handler = new ShowUserQueryHandler($userQueryRepository, $logger);
        $result = $handler($query);

        $this->assertSame($user, $result);
    }

    public function testInvokeUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $userEmail = 'test@example.com';
        $query = new ShowUserQuery($userEmail);

        $userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $userQueryRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $userEmail])
            ->willReturn(null);

        $logger->expects($this->once())
            ->method('error')
            ->with('User not found', ['email' => $userEmail]);

        $handler = new ShowUserQueryHandler($userQueryRepository, $logger);
        $handler($query);
    }
}
