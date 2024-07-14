<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\GetOneEnvelopeQuery;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\EnvelopeNotFoundException;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class GetOneEnvelopeQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(GetOneEnvelopeQuery $getOneEnvelopeQuery): EnvelopeInterface
    {
        try {
            $envelope = $this->envelopeQueryRepository->findOneBy(['id' => $getOneEnvelopeQuery->getEnvelopeId()]);

            if (!$envelope) {
                throw new EnvelopeNotFoundException(sprintf('Envelope not found with ID: %d', $getOneEnvelopeQuery->getEnvelopeId()));
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }

        return $envelope;
    }
}
