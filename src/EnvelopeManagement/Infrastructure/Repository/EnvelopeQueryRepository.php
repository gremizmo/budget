<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Repository;

use App\EnvelopeManagement\Domain\Repository\EnvelopeQueryRepositoryInterface;
use App\EnvelopeManagement\Domain\View\Envelope;
use App\EnvelopeManagement\Domain\View\EnvelopeInterface;
use App\EnvelopeManagement\Domain\View\EnvelopesPaginated;
use App\EnvelopeManagement\Domain\View\EnvelopesPaginatedInterface;
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
        $sql = sprintf('SELECT * FROM envelope_view WHERE %s LIMIT 1', $this->buildWhereClause($criteria));
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery($criteria)->fetchAssociative();

        return $result ? Envelope::create($result) : null;
    }

    /**
     * @throws Exception
     */
    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): EnvelopesPaginatedInterface {
        $sql = sprintf('SELECT * FROM envelope_wiew WHERE %s', $this->buildWhereClause($criteria));

        if ($orderBy) {
            $sql = sprintf(
                '%s ORDER BY %s',
                $sql,
                implode(
                    ', ',
                    array_map(fn ($key, $value) => sprintf(
                        '%s %s',
                        $key,
                        $value,
                    ), array_keys($orderBy), $orderBy),
                )
            );
        }

        if ($limit) {
            $sql = sprintf('%s LIMIT %d', $sql, $limit);
        }

        if ($offset) {
            $sql = sprintf('%s OFFSET %d', $sql, $offset);
        }

        $stmt = $this->connection->prepare($sql);
        $query = $stmt->executeQuery($this->filterCriteria($criteria));
        $results = $query->fetchAllAssociative();
        $count = $query->rowCount();

        return new EnvelopesPaginated(
            array_map([$this, 'mapToEnvelopeModel'], $results),
            $count,
        );
    }

    private function buildWhereClause(array $criteria): string
    {
        return implode(
            ' AND ',
            array_map(fn ($key, $value) => null === $value ? sprintf('%s IS NULL', $key) :
                sprintf('%s = :%s', $key, $key), array_keys($criteria), $criteria),
        );
    }

    private function filterCriteria(array $criteria): array
    {
        return array_filter($criteria, fn ($value) => null !== $value);
    }

    private function mapToEnvelopeModel(array $data): EnvelopeInterface
    {
        return Envelope::create($data);
    }
}
