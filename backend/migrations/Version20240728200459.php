<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240728200459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration adds the user table and updates the envelope table to have a user_id column.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, consent_given TINYINT(1) NOT NULL, roles JSON NOT NULL, consent_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE envelope ADD user_id INT NOT NULL, DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE envelope ADD CONSTRAINT FK_8A957868A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8A957868A76ED395 ON envelope (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE envelope DROP FOREIGN KEY FK_8A957868A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_8A957868A76ED395 ON envelope');
        $this->addSql('ALTER TABLE envelope ADD created_by VARCHAR(255) NOT NULL, ADD updated_by VARCHAR(255) NOT NULL, DROP user_id');
    }
}
