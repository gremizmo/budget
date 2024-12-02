<?php

namespace App\UserManagement\Application\QueryHandler;

use App\UserManagement\Application\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Domain\Model\UserInterface;
use App\UserManagement\Domain\Repository\UserQueryRepositoryInterface;

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
