<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240824121644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration drops the envelope_history table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE envelope_history');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE envelope_history (id INT AUTO_INCREMENT NOT NULL, envelope_id INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, changes JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }
}
