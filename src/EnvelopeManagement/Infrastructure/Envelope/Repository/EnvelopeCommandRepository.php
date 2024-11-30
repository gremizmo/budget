<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\Repository;

use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Repository\EnvelopeCommandRepositoryInterface;
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
        $this->connection->insert('envelope', [
            'uuid' => $envelope->getUuid(),
            'created_at' => $envelope->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $envelope->getUpdatedAt()->format('Y-m-d H:i:s'),
            'current_budget' => $envelope->getCurrentBudget(),
            'target_budget' => $envelope->getTargetBudget(),
            'title' => $envelope->getTitle(),
            'parent_uuid' => $envelope->getParent()?->getUuid(),
            'user_uuid' => $envelope->getUserUuid(),
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
