<?php

namespace App\Application\User\QueryHandler;

use App\Application\User\Query\GetUserByPasswordResetTokenQuery;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Repository\UserQueryRepositoryInterface;

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
