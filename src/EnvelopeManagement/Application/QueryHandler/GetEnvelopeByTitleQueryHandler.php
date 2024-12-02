<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\QueryHandler;

use App\EnvelopeManagement\Application\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;

readonly class GetEnvelopeByTitleQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ) {
    }

    public function __invoke(GetEnvelopeByTitleQuery $getOneEnvelopeQuery): ?EnvelopeInterface
    {
        return $this->envelopeQueryRepository->findOneBy([
            'title' => $getOneEnvelopeQuery->getTitle(),
            'user_uuid' => $getOneEnvelopeQuery->getUserUuid(),
        ]);
    }
}
