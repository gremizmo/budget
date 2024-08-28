<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;

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
            'user' => $getOneEnvelopeQuery->getUser()->getId(),
        ]);
    }
}
