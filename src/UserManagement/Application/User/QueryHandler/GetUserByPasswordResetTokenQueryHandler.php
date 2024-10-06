<?php

namespace App\UserManagement\Application\User\QueryHandler;

use App\UserManagement\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;

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
