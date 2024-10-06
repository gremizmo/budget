<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\QueryHandler;

use App\EnvelopeManagement\Application\Envelope\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Envelope\Adapter\LoggerInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;

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
            'userUuid' => $getOneEnvelopeQuery->getUserUuid(),
        ]);

        if (!$envelope) {
            $this->logger->error('Envelope not found', [
                'uuid' => $getOneEnvelopeQuery->getEnvelopeUuid(),
                'userUuid' => $getOneEnvelopeQuery->getUserUuid(),
            ]);
            throw new EnvelopeNotFoundException(EnvelopeNotFoundException::MESSAGE, 404);
        }

        return $envelope;
    }
}
