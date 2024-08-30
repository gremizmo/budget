<?php

declare(strict_types=1);

namespace App\Application\User\QueryHandler;

use App\Application\User\Query\GetUserAlreadyExistsQuery;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\User\Repository\UserQueryRepositoryInterface;

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
