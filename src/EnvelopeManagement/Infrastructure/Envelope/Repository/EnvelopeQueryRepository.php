<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\Repository;

use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\Envelope\View\Envelope;
use App\EnvelopeManagement\Domain\Envelope\View\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\View\EnvelopesPaginated;
use App\EnvelopeManagement\Domain\Envelope\View\EnvelopesPaginatedInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class EnvelopeQueryRepository implements EnvelopeQueryRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?EnvelopeInterface
    {
        $sql = sprintf('SELECT * FROM envelope WHERE %s LIMIT 1', $this->buildWhereClause($criteria));
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery($criteria)->fetchAssociative();

        return $result ? Envelope::createFromQueryRepository($result) : null;
    }

    /**
     * @throws Exception
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): EnvelopesPaginatedInterface
    {
        $sql = sprintf('SELECT * FROM envelope WHERE %s', $this->buildWhereClause($criteria));

        if ($orderBy) {
            $sql .= sprintf(' ORDER BY %s', implode(', ', array_map(fn($key, $value) => sprintf('%s %s', $key, $value), array_keys($orderBy), $orderBy)));
        }

        if ($limit) {
            $sql .= sprintf(' LIMIT %d', $limit);
        }

        if ($offset) {
            $sql .= sprintf(' OFFSET %d', $offset);
        }

        $stmt = $this->connection->prepare($sql);
        $query = $stmt->executeQuery($this->filterCriteria($criteria));
        $results = $query->fetchAllAssociative();
        $count = $query->rowCount();

        return new EnvelopesPaginated(
            array_map([$this, 'mapToEnvelopeModel'], $results ?? []),
            $count,
        );
    }

    private function buildWhereClause(array $criteria): string
    {
        return implode(' AND ', array_map(function ($key, $value) {
            return $value === null ? sprintf('%s IS NULL', $key) : sprintf('%s = :%s', $key, $key);
        }, array_keys($criteria), $criteria));
    }

    private function filterCriteria(array $criteria): array
    {
        return array_filter($criteria, fn($value) => $value !== null);
    }

    private function mapToEnvelopeModel(array $data): EnvelopeInterface
    {
        return Envelope::createFromQueryRepository($data);
    }
}
