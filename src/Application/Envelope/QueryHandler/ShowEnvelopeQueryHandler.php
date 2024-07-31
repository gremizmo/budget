<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\EnvelopeNotFoundException;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

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
            'id' => $getOneEnvelopeQuery->getEnvelopeId(),
            'user' => $getOneEnvelopeQuery->getUser()->getId(),
        ]);

        if (!$envelope) {
            $this->logger->error('Envelope not found', [
                'id' => $getOneEnvelopeQuery->getEnvelopeId(),
                'user_id' => $getOneEnvelopeQuery->getUser()->getId(),
            ]);
            throw new EnvelopeNotFoundException(EnvelopeNotFoundException::MESSAGE, 404);
        }

        return $envelope;
    }
}
