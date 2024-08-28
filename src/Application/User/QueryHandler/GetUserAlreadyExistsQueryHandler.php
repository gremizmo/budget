<?php

declare(strict_types=1);

namespace App\Application\User\QueryHandler;

use App\Application\User\Query\GetUserAlreadyExistsQuery;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Repository\UserQueryRepositoryInterface;

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
