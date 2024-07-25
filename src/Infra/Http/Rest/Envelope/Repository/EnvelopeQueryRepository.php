<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Repository;

use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Exception\EnvelopeQueryRepositoryException;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Repository;

class EnvelopeQueryRepository extends Repository implements EnvelopeQueryRepositoryInterface
{
    public function __construct(
        protected PaginatedFinderInterface $finder,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($finder);
    }

    /**
     * @throws \Throwable
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Envelope
    {
        $query = new Query();
        $query->setQuery(new Query\Term($criteria));

        try {
            $result = $this->find($query);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            throw new EnvelopeQueryRepositoryException(
                'An Error occurred in method findOneBy of EnvelopeQueryRepository',
                $exception->getCode(),
                $exception,
            );
        }

        $envelope = reset($result);

        return $envelope instanceof Envelope ? $envelope : null;
    }

    /**
     * @throws \Throwable
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $query = new Query();
        $nestedQuery = new Query\BoolQuery();
        isset($criteria['parent']) ?
            $nestedQuery->addMust(new Query\Term(['parent.id' => $criteria['parent']])) :
            $nestedQuery->addMustNot(new Query\Exists('parent.id'));
        $query->setQuery($nestedQuery);
        $query->setFrom($offset ?? 0);
        $query->setSize($limit ?? 10);
        $query->setSort($orderBy ?? []);

        try {
            return $this->find($query);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            throw new EnvelopeQueryRepositoryException(
                'An Error occurred in method findBy of EnvelopeQueryRepository',
                $exception->getCode(),
                $exception,
            );
        }
    }
}
