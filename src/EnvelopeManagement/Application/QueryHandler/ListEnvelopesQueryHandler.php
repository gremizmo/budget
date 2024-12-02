<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\QueryHandler;

use App\EnvelopeManagement\Application\Query\ListEnvelopesQuery;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\EnvelopesPaginatedInterface;

readonly class ListEnvelopesQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ) {
    }

    public function __invoke(ListEnvelopesQuery $listEnvelopesQuery): EnvelopesPaginatedInterface
    {
        $listEnvelopesDto = $listEnvelopesQuery->getListEnvelopesDto();

        return $this->envelopeQueryRepository->findBy(
            [
                'user_uuid' => $listEnvelopesQuery->getUserUuid(),
                'parent_uuid' => $listEnvelopesDto->getParentUuid(),
            ],
            $listEnvelopesDto->getOrderBy(),
            $listEnvelopesDto->getLimit(),
            $listEnvelopesDto->getOffset(),
        );
    }
}
