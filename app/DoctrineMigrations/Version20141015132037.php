<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141015132037 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formats ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formats ADD CONSTRAINT FK_DBCBA3C32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_DBCBA3C32C8A3DE ON formats (organization_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formats DROP FOREIGN KEY FK_DBCBA3C32C8A3DE');
        $this->addSql('DROP INDEX IDX_DBCBA3C32C8A3DE ON formats');
        $this->addSql('ALTER TABLE formats DROP organization_id');
    }
}
