<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904200743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_log (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', path_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', course_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', began_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', completed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FAB3EEF7D96C566B (path_id), INDEX IDX_FAB3EEF7591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_log ADD CONSTRAINT FK_FAB3EEF7D96C566B FOREIGN KEY (path_id) REFERENCES path (id)');
        $this->addSql('ALTER TABLE course_log ADD CONSTRAINT FK_FAB3EEF7591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE course CHANGE level level int(1) NOT NULL');
        $this->addSql('ALTER TABLE player CHANGE gender gender varchar(7) NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE level level int(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_log DROP FOREIGN KEY FK_FAB3EEF7D96C566B');
        $this->addSql('ALTER TABLE course_log DROP FOREIGN KEY FK_FAB3EEF7591CC992');
        $this->addSql('DROP TABLE course_log');
        $this->addSql('ALTER TABLE course CHANGE level level INT NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE level level INT NOT NULL');
        $this->addSql('ALTER TABLE player CHANGE gender gender VARCHAR(7) NOT NULL');
    }
}
