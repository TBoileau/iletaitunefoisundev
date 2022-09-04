<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904163338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', training_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, excerpt LONGTEXT NOT NULL, content LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, video VARCHAR(255) NOT NULL, thread JSON NOT NULL, level int(1) NOT NULL, INDEX IDX_169E6FB9BEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE player CHANGE gender gender varchar(7) NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE level level int(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9BEFD98D1');
        $this->addSql('DROP TABLE course');
        $this->addSql('ALTER TABLE training CHANGE level level INT NOT NULL');
        $this->addSql('ALTER TABLE player CHANGE gender gender VARCHAR(7) NOT NULL');
    }
}
