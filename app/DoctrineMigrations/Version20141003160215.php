<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141003160215 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP INDEX UNIQ_1483A5E9DE12AB56, ADD INDEX IDX_1483A5E9DE12AB56 (created_by)');
        $this->addSql('ALTER TABLE users DROP INDEX UNIQ_1483A5E916FE72E1, ADD INDEX IDX_1483A5E916FE72E1 (updated_by)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E916FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP INDEX IDX_1483A5E9DE12AB56, ADD UNIQUE INDEX UNIQ_1483A5E9DE12AB56 (created_by)');
        $this->addSql('ALTER TABLE users DROP INDEX IDX_1483A5E916FE72E1, ADD UNIQUE INDEX UNIQ_1483A5E916FE72E1 (updated_by)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9DE12AB56');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E916FE72E1');
    }
}
