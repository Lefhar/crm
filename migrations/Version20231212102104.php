<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212102104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE time_tracking (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, starttime DATETIME NOT NULL, endtime DATETIME NOT NULL, hours DOUBLE PRECISION NOT NULL, INDEX IDX_CF921D08DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE time_tracking ADD CONSTRAINT FK_CF921D08DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE task ADD status VARCHAR(255) NOT NULL, ADD progress INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_tracking DROP FOREIGN KEY FK_CF921D08DB60186');
        $this->addSql('DROP TABLE time_tracking');
        $this->addSql('ALTER TABLE task DROP status, DROP progress');
    }
}
