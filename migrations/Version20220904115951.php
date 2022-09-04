<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904115951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE path (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', player_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', training_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', began_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B548B0F99E6F5DF (player_id), INDEX IDX_B548B0FBEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0F99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0FBEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE player CHANGE gender gender varchar(7) NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE level level int(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0F99E6F5DF');
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0FBEFD98D1');
        $this->addSql('DROP TABLE path');
        $this->addSql('ALTER TABLE training CHANGE level level INT NOT NULL');
        $this->addSql('ALTER TABLE player CHANGE gender gender VARCHAR(7) NOT NULL');
    }
}
