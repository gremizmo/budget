<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Domain\Envelope\Model\EnvelopesPaginatedInterface;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

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
                    'user' => $listEnvelopesQuery->getUser()->getId(),
                    'parent' => $listEnvelopesDto->getParentId(),
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
