<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;

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
