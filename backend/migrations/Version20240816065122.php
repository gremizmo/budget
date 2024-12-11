<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240816065122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration adds password reset token and expiry to the user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD password_reset_token VARCHAR(64) DEFAULT NULL, ADD password_reset_token_expiry DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP password_reset_token, DROP password_reset_token_expiry');
    }
}
