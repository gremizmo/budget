<?php

declare(strict_types=1);

namespace App\Application\User\QueryHandler;

use App\Application\User\Query\ShowUserQuery;
use App\Domain\User\Entity\UserInterface;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

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
