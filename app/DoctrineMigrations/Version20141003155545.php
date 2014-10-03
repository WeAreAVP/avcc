<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141003155545 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E932C8A3DE');
        $this->addSql('DROP INDEX UNIQ_1483A5E9DE12AB56 ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E916FE72E1 ON users');
        $this->addSql('DROP INDEX IDX_1483A5E932C8A3DE ON users');
        $this->addSql('ALTER TABLE users ADD users_created_id INT DEFAULT NULL, ADD users_updated_id INT DEFAULT NULL, DROP organization_id, DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9898BB1D0 FOREIGN KEY (users_created_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E941676867 FOREIGN KEY (users_updated_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9898BB1D0 ON users (users_created_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E941676867 ON users (users_updated_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9898BB1D0');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E941676867');
        $this->addSql('DROP INDEX IDX_1483A5E9898BB1D0 ON users');
        $this->addSql('DROP INDEX IDX_1483A5E941676867 ON users');
        $this->addSql('ALTER TABLE users ADD organization_id INT DEFAULT NULL, ADD created_by INT DEFAULT NULL, ADD updated_by INT DEFAULT NULL, DROP users_created_id, DROP users_updated_id');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E932C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9DE12AB56 ON users (created_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E916FE72E1 ON users (updated_by)');
        $this->addSql('CREATE INDEX IDX_1483A5E932C8A3DE ON users (organization_id)');
    }
}
