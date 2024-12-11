<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Repository;

use App\EnvelopeManagement\Domain\Repository\EnvelopeCommandRepositoryInterface;
use App\EnvelopeManagement\Domain\View\EnvelopeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class EnvelopeCommandRepository implements EnvelopeCommandRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function save(EnvelopeInterface $envelope): void
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
            'created_at' => $envelope->getCreatedAt(),
            'updated_at' => $envelope->getUpdatedAt(),
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
    public function delete(EnvelopeInterface $envelope): void
    {
        $this->connection->delete('envelope', ['uuid' => $envelope->getUuid()]);
    }
}
