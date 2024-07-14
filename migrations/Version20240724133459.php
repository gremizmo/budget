<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240724133459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration adds the envelope and envelope_history tables.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE envelope (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, current_budget VARCHAR(255) NOT NULL, target_budget VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_8A957868727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE envelope_history (id INT AUTO_INCREMENT NOT NULL, envelope_id INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(255) NOT NULL, changes JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE envelope ADD CONSTRAINT FK_8A957868727ACA70 FOREIGN KEY (parent_id) REFERENCES envelope (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE envelope DROP FOREIGN KEY FK_8A957868727ACA70');
        $this->addSql('DROP TABLE envelope');
        $this->addSql('DROP TABLE envelope_history');
    }
}
