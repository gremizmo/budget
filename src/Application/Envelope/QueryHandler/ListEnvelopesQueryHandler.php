<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Domain\Envelope\Entity\EnvelopeCollectionInterface;
use App\Domain\Envelope\Factory\EnvelopeCollectionFactoryInterface;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;

readonly class ListEnvelopesQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
        private EnvelopeCollectionFactoryInterface $envelopeCollectionFactory,
    ) {
    }

    public function __invoke(ListEnvelopesQuery $listEnvelopesQuery): EnvelopeCollectionInterface
    {
        // TODO: remove envelopeCollectionFactory when issue with doctrine collection is solved
        return $this->envelopeCollectionFactory->create(
            $this->envelopeQueryRepository->findBy([
                'user' => $listEnvelopesQuery->getUser()->getId(),
            ])
        );
    }
}
