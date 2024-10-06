<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\QueryHandler;

use App\UserManagement\Application\User\Query\ShowUserQuery;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;

readonly class ShowUserQueryHandler
{
    public function __construct(
        private UserQueryRepositoryInterface $userQueryRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(ShowUserQuery $getOneUserQuery): UserInterface
    {
        $user = $this->userQueryRepository->findOneBy([
            'email' => $getOneUserQuery->getUserEmail(),
        ]);

        if (!$user) {
            $this->logger->error('User not found', [
                'email' => $getOneUserQuery->getUserEmail(),
            ]);

            throw new UserNotFoundException(UserNotFoundException::MESSAGE, 404);
        }

        return $user;
    }
}
