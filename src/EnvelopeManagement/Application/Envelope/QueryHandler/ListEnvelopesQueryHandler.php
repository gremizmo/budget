<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Envelope\Query\ListEnvelopesQuery;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopesPaginatedInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;

readonly class ListEnvelopesQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws ListEnvelopesQueryHandlerException
     */
    public function __invoke(ListEnvelopesQuery $listEnvelopesQuery): EnvelopesPaginatedInterface
    {
        $listEnvelopesDto = $listEnvelopesQuery->getListEnvelopesDto();

        try {
            return $this->envelopeQueryRepository->findBy(
                [
                    'userUuid' => $listEnvelopesQuery->getUserUuid(),
                    'parent' => $listEnvelopesDto->getParentUuid(),
                ],
                $listEnvelopesDto->getOrderBy(),
                $listEnvelopesDto->getLimit(),
                $listEnvelopesDto->getOffset(),
            );
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new ListEnvelopesQueryHandlerException(ListEnvelopesQueryHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}
