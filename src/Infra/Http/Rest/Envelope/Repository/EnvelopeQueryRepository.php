<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Repository;

use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopesPaginated;
use App\Domain\Envelope\Entity\EnvelopesPaginatedInterface;
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
     * @throws \Throwable
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): EnvelopesPaginatedInterface
    {
        $query = new Query();

        $query->setRawQuery(
            [
                'query' => [
                    'bool' => [
                        'must' => array_values(array_filter([
                            $this->filterByParent($criteria),
                            $this->filterByUser($criteria),
                        ])),
                    ],
                ],
            ]
        );
        $query->setFrom($offset ?? 0);
        $query->setSize($limit ?? 10);
        $query->setSort($orderBy ?? []);

        try {
            return new EnvelopesPaginated(
                $this->find($query),
                $this->count($criteria),
            );
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            throw new EnvelopeQueryRepositoryException(sprintf('%s on method findBy', EnvelopeQueryRepositoryException::MESSAGE), $exception->getCode(), $exception);
        }
    }

    private function count(array $criteria): int
    {
        $query = new Query();
        $query->setRawQuery(
            [
                'query' => [
                    'bool' => [
                        'must' => array_values(array_filter([
                            $this->filterByParent($criteria),
                            $this->filterByUser($criteria),
                        ])),
                    ],
                ],
            ]
        );

        return $this->finder->findPaginated($query)->getNbResults();
    }

    private function filterById(array $criteria): array
    {
        if (!isset($criteria['id'])) {
            return [];
        }

        return ['term' => ['id' => $criteria['id']]];
    }

    private function filterByTitle(array $criteria): array
    {
        if (!isset($criteria['title'])) {
            return [];
        }

        return ['term' => ['title' => $criteria['title']]];
    }

    private function filterByParent(array $criteria): array
    {
        if (!isset($criteria['parent'])) {
            return [];
        }

        return ['term' => ['parent.id' => $criteria['parent']]];
    }

    private function filterByUser(array $criteria): array
    {
        if (!isset($criteria['user'])) {
            return [];
        }

        return ['term' => ['user.id' => $criteria['user']]];
    }
}
