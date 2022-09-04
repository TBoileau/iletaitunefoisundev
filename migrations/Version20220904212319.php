<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904212319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course CHANGE level level int(1) NOT NULL');
        $this->addSql('ALTER TABLE path ADD completed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE player CHANGE gender gender varchar(7) NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE level level int(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player CHANGE gender gender VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE path DROP completed_at');
        $this->addSql('ALTER TABLE course CHANGE level level INT NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE level level INT NOT NULL');
    }
}
