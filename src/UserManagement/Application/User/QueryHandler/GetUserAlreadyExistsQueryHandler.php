<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\QueryHandler;

use App\UserManagement\Application\User\Query\GetUserAlreadyExistsQuery;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;

readonly class GetUserAlreadyExistsQueryHandler
{
    public function __construct(
        private UserQueryRepositoryInterface $userQueryRepository,
    ) {
    }

    public function __invoke(GetUserAlreadyExistsQuery $getUserAlreadyExistsQuery): ?UserInterface
    {
        return $this->userQueryRepository->findOneBy([
            'email' => $getUserAlreadyExistsQuery->getUserEmail(),
        ]);
    }
}
