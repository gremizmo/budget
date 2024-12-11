<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241026114243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration removes the id column from the envelope table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope DROP FOREIGN KEY FK_8A957868727ACA70');
        $this->addSql('ALTER TABLE envelope DROP COLUMN id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope ADD id INT AUTO_INCREMENT NOT NULL PRIMARY KEY');
        $this->addSql('ALTER TABLE envelope ADD CONSTRAINT FK_8A957868727ACA70 FOREIGN KEY (id) REFERENCES envelope(id)');
    }
}
