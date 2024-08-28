<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Repository;

use App\Domain\Envelope\Exception\Repository\EnvelopeQueryRepositoryException;
use App\Domain\Envelope\Model\EnvelopesPaginated;
use App\Domain\Envelope\Model\EnvelopesPaginatedInterface;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Infra\Http\Rest\Envelope\Entity\Envelope;
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
     * @param array<string, string> $criteria
     * @param array<string, string> $orderBy
     *
     * @throws \Throwable
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Envelope
    {
        $query = new Query();

        $query->setRawQuery(
            [
                'size' => 1,
                'query' => [
                    'bool' => [
                        'must' => array_values(array_filter([
                            $this->filterById($criteria),
                            $this->filterByTitle($criteria),
                            $this->filterByUser($criteria),
                        ])),
                    ],
                ],
            ]
        );

        try {
            $result = $this->find($query);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            throw new EnvelopeQueryRepositoryException(sprintf('%s on method findOneBy', EnvelopeQueryRepositoryException::MESSAGE), $exception->getCode(), $exception);
        }

        $envelope = reset($result);

        return $envelope instanceof Envelope ? $envelope : null;
    }

    /**
     * @param array<string, string> $criteria
     * @param array<string, string> $orderBy
     *
     * @throws \Throwable
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): EnvelopesPaginatedInterface
    {
        $query = new Query();

        $userFilters = $this->filterByUser($criteria);
        $parentFilters = $this->filterByParent($criteria);

        $mustFilters[] = $userFilters;
        $parentFilterMust = count($parentFilters['must']) > 0 ? $parentFilters['must'][0] : null;

        if ($parentFilterMust) {
            $mustFilters[] = $parentFilterMust;
        }

        $mustNotFilters = $parentFilters['must_not'] ?? [];

        $query->setRawQuery(
            [
                'query' => [
                    'bool' => [
                        'must' => $mustFilters,
                        'must_not' => $mustNotFilters,
                    ],
                ],
            ]
        );
        $count = $this->count($query);

        $query->setFrom($offset ?? 0);
        $query->setSize($limit ?? 10);
        $query->setSort($orderBy ?? []);

        try {
            return new EnvelopesPaginated(
                $this->find($query),
                $count,
            );
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            throw new EnvelopeQueryRepositoryException(sprintf('%s on method findBy', EnvelopeQueryRepositoryException::MESSAGE), $exception->getCode(), $exception);
        }
    }

    private function count(Query $query): int
    {
        return $this->finder->findPaginated($query)->getNbResults();
    }

    /**
     * @param array<string, string> $criteria
     *
     * @return array<string, array<string, string>>
     */
    private function filterById(array $criteria): array
    {
        if (!isset($criteria['id'])) {
            return [];
        }

        return ['term' => ['id' => $criteria['id']]];
    }

    /**
     * @param array<string, string> $criteria
     *
     * @return array<string, array<string, string>>
     */
    private function filterByTitle(array $criteria): array
    {
        if (!isset($criteria['title'])) {
            return [];
        }

        return ['term' => ['title' => $criteria['title']]];
    }

    /**
     * @param array<string, string> $criteria
     *
     * @return array<string, array<int, array<string, array<string, string>>>>
     */
    private function filterByParent(array $criteria): array
    {
        $filters = [
            'must' => [],
            'must_not' => [],
        ];

        if (isset($criteria['parent'])) {
            $filters['must'][] = ['term' => ['parent.id' => $criteria['parent']]];
        } else {
            $filters['must_not'][] = ['exists' => ['field' => 'parent']];
        }

        return $filters;
    }

    /**
     * @param array<string, string> $criteria
     *
     * @return array<string, array<string, string>>
     */
    private function filterByUser(array $criteria): array
    {
        if (!isset($criteria['user'])) {
            return [];
        }

        return ['term' => ['user.id' => $criteria['user']]];
    }
}
