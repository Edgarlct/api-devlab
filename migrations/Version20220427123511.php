<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427123511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD association_office_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495B47CB51 FOREIGN KEY (association_office_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6495B47CB51 ON user (association_office_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495B47CB51');
        $this->addSql('DROP INDEX IDX_8D93D6495B47CB51 ON user');
        $this->addSql('ALTER TABLE user DROP association_office_id');
    }
}
