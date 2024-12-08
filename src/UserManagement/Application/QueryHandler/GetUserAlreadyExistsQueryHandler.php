<?php

declare(strict_types=1);

namespace App\UserManagement\Application\QueryHandler;

use App\UserManagement\Application\Query\GetUserAlreadyExistsQuery;
use App\UserManagement\Domain\Model\UserInterface;
use App\UserManagement\Domain\Repository\UserQueryRepositoryInterface;

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
