<?php

declare(strict_types=1);

namespace App\UserManagement\Application\QueryHandler;

use App\UserManagement\Application\Query\ShowUserQuery;
use App\UserManagement\Domain\Model\UserInterface;
use App\UserManagement\Domain\Repository\UserQueryRepositoryInterface;

readonly class ShowUserQueryHandler
{
    public function __construct(
        private UserQueryRepositoryInterface $userQueryRepository,
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
            throw new UserNotFoundException(UserNotFoundException::MESSAGE, 404);
        }

        return $user;
    }
}
