<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Envelope\Query\ListEnvelopesQuery;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopesPaginatedInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;

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
                'userUuid' => $listEnvelopesQuery->getUserUuid(),
                'parent' => $listEnvelopesDto->getParentUuid(),
            ],
            $listEnvelopesDto->getOrderBy(),
            $listEnvelopesDto->getLimit(),
            $listEnvelopesDto->getOffset(),
        );
    }
}
