<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241026130513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration makes the parent_uuid column nullable in the envelope table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope MODIFY parent_uuid CHAR(36) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope MODIFY parent_uuid CHAR(36) NOT NULL');
    }
}
