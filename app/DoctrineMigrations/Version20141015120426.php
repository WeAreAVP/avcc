<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141015120426 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acid_detection_strips ADD organization_id INT NOT NULL');
        $this->addSql('ALTER TABLE acid_detection_strips ADD CONSTRAINT FK_DAB1064232C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_DAB1064232C8A3DE ON acid_detection_strips (organization_id)');
        $this->addSql('ALTER TABLE bases ADD organization_id INT NOT NULL');
        $this->addSql('ALTER TABLE bases ADD CONSTRAINT FK_217B2A3B32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_217B2A3B32C8A3DE ON bases (organization_id)');
        $this->addSql('ALTER TABLE cassette_sizes ADD organization_id INT NOT NULL');
        $this->addSql('ALTER TABLE cassette_sizes ADD CONSTRAINT FK_2007DC8B32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2007DC8B32C8A3DE ON cassette_sizes (organization_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acid_detection_strips DROP FOREIGN KEY FK_DAB1064232C8A3DE');
        $this->addSql('DROP INDEX IDX_DAB1064232C8A3DE ON acid_detection_strips');
        $this->addSql('ALTER TABLE acid_detection_strips DROP organization_id');
        $this->addSql('ALTER TABLE bases DROP FOREIGN KEY FK_217B2A3B32C8A3DE');
        $this->addSql('DROP INDEX IDX_217B2A3B32C8A3DE ON bases');
        $this->addSql('ALTER TABLE bases DROP organization_id');
        $this->addSql('ALTER TABLE cassette_sizes DROP FOREIGN KEY FK_2007DC8B32C8A3DE');
        $this->addSql('DROP INDEX IDX_2007DC8B32C8A3DE ON cassette_sizes');
        $this->addSql('ALTER TABLE cassette_sizes DROP organization_id');
    }
}
