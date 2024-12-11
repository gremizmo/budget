<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241201212739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the envelope_view and event_store tables for storing envelope data and domain events.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE envelope_view (
                uuid CHAR(36) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                current_budget VARCHAR(255) NOT NULL,
                target_budget VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                user_uuid CHAR(36) NOT NULL,
                is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
                PRIMARY KEY (uuid)
            )
        ');
        $this->addSql('CREATE INDEX idx_envelope_view_user_uuid ON envelope_view (user_uuid)');

        $this->addSql('
            CREATE TABLE event_store (
                aggregate_id CHAR(36) NOT NULL,
                type VARCHAR(255) NOT NULL,
                payload JSON NOT NULL,
                occurred_on DATETIME NOT NULL
            )
        ');
        $this->addSql('CREATE INDEX idx_event_store_aggregate_id ON event_store (aggregate_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE envelope_view');
        $this->addSql('DROP TABLE event_store');
    }
}
