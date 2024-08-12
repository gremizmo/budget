<?php

declare(strict_types=1);

namespace App\Tests\Application\User\QueryHandler;

use App\Application\User\Query\GetUserAlreadyExistsQuery;
use App\Application\User\QueryHandler\GetUserAlreadyExistsQueryHandler;
use App\Domain\User\Entity\UserInterface;
use App\Domain\User\Repository\UserQueryRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetUserAlreadyExistsQueryHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $userEmail = 'test@example.com';
        $query = new GetUserAlreadyExistsQuery($userEmail);

        $user = $this->createMock(UserInterface::class);
        $userQueryRepository = $this->createMock(UserQueryRepositoryInterface::class);

        $userQueryRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $userEmail])
            ->willReturn($user);

        $handler = new GetUserAlreadyExistsQueryHandler($userQueryRepository);
        $result = $handler($query);

        $this->assertSame($user, $result);
    }
}
