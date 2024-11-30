<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241026115206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration replaces parent_id with parent_uuid in the envelope table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope DROP COLUMN parent_id');
        $this->addSql('ALTER TABLE envelope ADD parent_uuid CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE envelope ADD CONSTRAINT FK_8A957868727ACA70 FOREIGN KEY (parent_uuid) REFERENCES envelope(uuid)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope DROP FOREIGN KEY FK_8A957868727ACA70');
        $this->addSql('ALTER TABLE envelope DROP COLUMN parent_uuid');
        $this->addSql('ALTER TABLE envelope ADD parent_id INT NOT NULL');
        $this->addSql('ALTER TABLE envelope ADD CONSTRAINT FK_8A957868727ACA70 FOREIGN KEY (parent_id) REFERENCES envelope(id)');
    }
}
