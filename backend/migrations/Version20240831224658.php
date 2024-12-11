<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240831224658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration removes the foreign key constraint from the envelope table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope DROP FOREIGN KEY FK_8A957868A76ED395');
        $this->addSql('DROP INDEX IDX_8A957868A76ED395 ON envelope');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope ADD CONSTRAINT FK_8A957868A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8A957868A76ED395 ON envelope (user_id)');
    }
}
