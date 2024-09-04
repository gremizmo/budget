<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\QueryHandler;

use App\UserManagement\Application\User\Query\GetUserAlreadyExistsQuery;
use App\UserManagement\Domain\User\Adapter\LoggerInterface;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\Domain\User\Repository\UserQueryRepositoryInterface;

readonly class GetUserAlreadyExistsQueryHandler
{
    public function __construct(
        private UserQueryRepositoryInterface $userQueryRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws GetUserAlreadyExistsQueryHandlerException
     */
    public function __invoke(GetUserAlreadyExistsQuery $getUserAlreadyExistsQuery): ?UserInterface
    {
        try {
            return $this->userQueryRepository->findOneBy([
                'email' => $getUserAlreadyExistsQuery->getUserEmail(),
            ]);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new GetUserAlreadyExistsQueryHandlerException(GetUserAlreadyExistsQueryHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
