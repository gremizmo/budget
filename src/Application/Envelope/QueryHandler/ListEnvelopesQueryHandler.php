<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Domain\Envelope\Model\EnvelopesPaginatedInterface;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;

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
                'user' => $listEnvelopesQuery->getUser()->getId(),
                'parent' => $listEnvelopesDto->getParentId(),
            ],
            $listEnvelopesDto->getOrderBy(),
            $listEnvelopesDto->getLimit(),
            $listEnvelopesDto->getOffset(),
        );
    }
}
