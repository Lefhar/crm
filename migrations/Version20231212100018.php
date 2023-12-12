<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212100018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoryclient DROP FOREIGN KEY FK_A54FA780A76ED395');
        $this->addSql('DROP INDEX IDX_A54FA780A76ED395 ON categoryclient');
        $this->addSql('ALTER TABLE categoryclient DROP user_id');
        $this->addSql('ALTER TABLE user ADD categoryclient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498051A83A FOREIGN KEY (categoryclient_id) REFERENCES categoryclient (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498051A83A ON user (categoryclient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoryclient ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE categoryclient ADD CONSTRAINT FK_A54FA780A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A54FA780A76ED395 ON categoryclient (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498051A83A');
        $this->addSql('DROP INDEX IDX_8D93D6498051A83A ON user');
        $this->addSql('ALTER TABLE user DROP categoryclient_id');
    }
}
