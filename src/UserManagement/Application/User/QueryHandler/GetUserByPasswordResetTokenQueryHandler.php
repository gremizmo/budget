<?php

namespace App\UserManagement\Application\User\QueryHandler;

use App\UserManagement\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\UserManagement\Domain\Shared\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;

readonly class GetUserByPasswordResetTokenQueryHandler
{
    public function __construct(
        private UserQueryRepositoryInterface $userQueryRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws GetUserByPasswordResetTokenQueryHandlerException
     */
    public function __invoke(GetUserByPasswordResetTokenQuery $getUserByPasswordResetTokenQuery): UserInterface
    {
        try {
            $user = $this->userQueryRepository->findOneBy([
                'passwordResetToken' => $getUserByPasswordResetTokenQuery->getUserPasswordResetToken(),
            ]);

            if (!$user instanceof UserInterface) {
                throw new UserNotFoundException('User not found with password reset token', 404);
            }

            return $user;
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new GetUserByPasswordResetTokenQueryHandlerException(GetUserByPasswordResetTokenQueryHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
