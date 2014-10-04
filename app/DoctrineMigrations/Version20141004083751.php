<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141004083751 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organizations DROP INDEX UNIQ_427C1C7FDE12AB56, ADD INDEX IDX_427C1C7FDE12AB56 (created_by)');
        $this->addSql('ALTER TABLE organizations DROP INDEX UNIQ_427C1C7F16FE72E1, ADD INDEX IDX_427C1C7F16FE72E1 (updated_by)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organizations DROP INDEX IDX_427C1C7FDE12AB56, ADD UNIQUE INDEX UNIQ_427C1C7FDE12AB56 (created_by)');
        $this->addSql('ALTER TABLE organizations DROP INDEX IDX_427C1C7F16FE72E1, ADD UNIQUE INDEX UNIQ_427C1C7F16FE72E1 (updated_by)');
    }
}
