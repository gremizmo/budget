<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240903203637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration adds uuid to envelope and user tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope ADD uuid VARCHAR(100) NOT NULL, ADD user_uuid VARCHAR(100) NOT NULL, DROP user_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A957868D17F50A6 ON envelope (uuid)');
        $this->addSql('ALTER TABLE user ADD uuid VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D17F50A6 ON user (uuid)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_8A957868D17F50A6 ON envelope');
        $this->addSql('ALTER TABLE envelope ADD user_id INT NOT NULL, DROP uuid, DROP user_uuid');
        $this->addSql('DROP INDEX UNIQ_8D93D649D17F50A6 ON user');
        $this->addSql('ALTER TABLE user DROP uuid');
    }
}
