<?php

declare(strict_types=1);

namespace App\Application\User\QueryHandler;

use App\Application\User\Query\ShowUserQuery;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Repository\UserQueryRepositoryInterface;

readonly class ShowUserQueryHandler
{
    public function __construct(
        private UserQueryRepositoryInterface $userQueryRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws ShowUserQueryHandlerException
     */
    public function __invoke(ShowUserQuery $getOneUserQuery): UserInterface
    {
        try {
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
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new ShowUserQueryHandlerException(ShowUserQueryHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
