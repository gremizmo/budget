<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212145625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE event_store (aggregate_id CHAR(36) NOT NULL, type VARCHAR(255) NOT NULL, payload JSON NOT NULL, occurred_on DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX idx_event_store_aggregate_id ON event_store (aggregate_id)');
        $this->addSql('CREATE TABLE envelope_view (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, current_budget VARCHAR(13) NOT NULL, target_budget VARCHAR(13) NOT NULL, name VARCHAR(50) NOT NULL, user_uuid VARCHAR(36) NOT NULL, is_deleted TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_9EA565B0D17F50A6 (uuid), INDEX idx_envelope_view_user_uuid (user_uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_view (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, consent_given TINYINT(1) NOT NULL, consent_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, roles JSON NOT NULL, is_deleted TINYINT(1) NOT NULL, password_reset_token VARCHAR(64) DEFAULT NULL, password_reset_token_expiry DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_store');
        $this->addSql('DROP TABLE envelope_view');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE user_view');
    }
}
