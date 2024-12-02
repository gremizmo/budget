<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\QueryHandler;

use App\EnvelopeManagement\Application\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Adapter\LoggerInterface;
use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;

readonly class ShowEnvelopeQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
        private LoggerInterface $logger,
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
        ]);

        if (!$envelope) {
            $this->logger->error('Envelope not found', [
                'uuid' => $getOneEnvelopeQuery->getEnvelopeUuid(),
                'user_uuid' => $getOneEnvelopeQuery->getUserUuid(),
            ]);
            throw new EnvelopeNotFoundException(EnvelopeNotFoundException::MESSAGE, 404);
        }

        return $envelope;
    }
}
