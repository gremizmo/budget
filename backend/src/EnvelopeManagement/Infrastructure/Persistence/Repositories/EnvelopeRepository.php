<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Persistence\Repositories;

use App\EnvelopeManagement\Domain\Ports\Inbound\EnvelopeRepositoryInterface;
use App\EnvelopeManagement\ReadModels\Views\EnvelopeView;
use App\EnvelopeManagement\ReadModels\Views\EnvelopeViewInterface;
use App\EnvelopeManagement\ReadModels\Views\EnvelopesPaginated;
use App\EnvelopeManagement\ReadModels\Views\EnvelopesPaginatedInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class EnvelopeRepository implements EnvelopeRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    #[\Override]
    public function save(EnvelopeViewInterface $envelope): void
    {
        $this->connection->executeStatement('
    INSERT INTO envelope_view (uuid, created_at, updated_at, current_budget, target_budget, name, user_uuid, is_deleted)
    VALUES (:uuid, :created_at, :updated_at, :current_budget, :target_budget, :name, :user_uuid, :is_deleted)
    ON DUPLICATE KEY UPDATE
        updated_at = VALUES(updated_at),
        current_budget = VALUES(current_budget),
        target_budget = VALUES(target_budget),
        name = VALUES(name),
        user_uuid = VALUES(user_uuid),
        is_deleted = VALUES(is_deleted)
', [
            'uuid' => $envelope->getUuid(),
            'created_at' => $envelope->getCreatedAt()->format(\DateTimeImmutable::ATOM),
            'updated_at' => $envelope->getUpdatedAt()->format(\DateTime::ATOM),
            'current_budget' => $envelope->getCurrentBudget(),
            'target_budget' => $envelope->getTargetBudget(),
            'name' => $envelope->getName(),
            'user_uuid' => $envelope->getUserUuid(),
            'is_deleted' => $envelope->isDeleted() ? 1 : 0,
        ]);
    }

    /**
     * @throws Exception
     */
    #[\Override]
    public function delete(EnvelopeViewInterface $envelope): void
    {
        $this->connection->delete('envelope', ['uuid' => $envelope->getUuid()]);
    }

    /**
     * @throws Exception
     */
    #[\Override]
    public function findOneBy(array $criteria, ?array $orderBy = null): ?EnvelopeViewInterface
    {
        $sql = sprintf('SELECT * FROM envelope_view WHERE %s LIMIT 1', $this->buildWhereClause($criteria));
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery($criteria)->fetchAssociative();

        return $result ? EnvelopeView::createFromRepository($result) : null;
    }

    /**
     * @throws Exception
     */
    #[\Override]
    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): EnvelopesPaginatedInterface {
        $sql = sprintf('SELECT * FROM envelope_view WHERE %s', $this->buildWhereClause($criteria));

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
            array_map([$this, 'mapToEnvelopeView'], $results),
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

    private function mapToEnvelopeView(array $data): EnvelopeViewInterface
    {
        return EnvelopeView::createFromRepository($data);
    }
}
