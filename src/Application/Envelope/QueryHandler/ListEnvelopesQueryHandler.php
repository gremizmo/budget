<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Domain\Envelope\Entity\EnvelopeCollectionInterface;
use App\Domain\Envelope\Factory\EnvelopeCollectionFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class ListEnvelopesQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
        private EnvelopeCollectionFactoryInterface $envelopeCollectionFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ListEnvelopesQuery $listEnvelopesQuery): EnvelopeCollectionInterface
    {
        try {
            $envelopes = $this->envelopeQueryRepository->findBy([
                'parent' => $listEnvelopesQuery->getEnvelopeId(),
            ]);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }

        return $this->envelopeCollectionFactory->create($envelopes);
    }
}
