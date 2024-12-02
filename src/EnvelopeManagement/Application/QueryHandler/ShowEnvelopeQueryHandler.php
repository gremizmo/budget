<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\QueryHandler;

use App\EnvelopeManagement\Application\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\EnvelopeInterface;

readonly class ShowEnvelopeQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
    ) {
    }

    /**
     * @throws EnvelopeNotFoundException
     */
    public function __invoke(ShowEnvelopeQuery $getOneEnvelopeQuery): EnvelopeInterface
    {
        $envelope = $this->envelopeQueryRepository->findOneBy([
            'uuid' => $getOneEnvelopeQuery->getEnvelopeUuid(),
            'user_uuid' => $getOneEnvelopeQuery->getUserUuid(),
            'is_deleted' => false,
        ]);

        if (!$envelope) {
            throw new EnvelopeNotFoundException(EnvelopeNotFoundException::MESSAGE, 404);
        }

        return $envelope;
    }
}
