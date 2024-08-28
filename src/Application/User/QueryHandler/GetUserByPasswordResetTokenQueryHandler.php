<?php

namespace App\Application\User\QueryHandler;

use App\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserQueryRepositoryInterface;

readonly class GetUserByPasswordResetTokenQueryHandler
{
    public function __construct(
        private UserQueryRepositoryInterface $userQueryRepository,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(GetUserByPasswordResetTokenQuery $getUserByPasswordResetTokenQuery): UserInterface
    {
        $user = $this->userQueryRepository->findOneBy([
            'passwordResetToken' => $getUserByPasswordResetTokenQuery->getUserPasswordResetToken(),
        ]);

        if (!$user instanceof UserInterface) {
            throw new UserNotFoundException('User not found with password reset token', 404);
        }

        return $user;
    }
}
