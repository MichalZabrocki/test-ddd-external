<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260803134612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tasks (name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status VARCHAR(32) NOT NULL, id CHAR(36) NOT NULL, assigned_user CHAR(36) DEFAULT NULL, INDEX idx_assigned_user (assigned_user), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_5058659764EB2CB0 FOREIGN KEY (assigned_user) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_5058659764EB2CB0');
        $this->addSql('DROP TABLE tasks');
    }
}
