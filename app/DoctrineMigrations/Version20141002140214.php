<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141002140214 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE organizations_users');
        $this->addSql('ALTER TABLE organizations DROP FOREIGN KEY FK_427C1C7F16FE72E1');
        $this->addSql('ALTER TABLE organizations DROP FOREIGN KEY FK_427C1C7FDE12AB56');
        $this->addSql('DROP INDEX UNIQ_427C1C7FDE12AB56 ON organizations');
        $this->addSql('DROP INDEX UNIQ_427C1C7F16FE72E1 ON organizations');
        $this->addSql('ALTER TABLE organizations DROP updated_by, DROP created_by');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E916FE72E1');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9DE12AB56');
        $this->addSql('DROP INDEX UNIQ_1483A5E9DE12AB56 ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E916FE72E1 ON users');
        $this->addSql('ALTER TABLE users ADD organization_id_id INT DEFAULT NULL, DROP updated_by, DROP created_by');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9F1C37890 FOREIGN KEY (organization_id_id) REFERENCES organizations (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9F1C37890 ON users (organization_id_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE organizations_users (user_id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_9328CA68A76ED395 (user_id), INDEX IDX_9328CA6832C8A3DE (organization_id), PRIMARY KEY(user_id, organization_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organizations_users ADD CONSTRAINT FK_9328CA6832C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id)');
        $this->addSql('ALTER TABLE organizations_users ADD CONSTRAINT FK_9328CA68A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE organizations ADD updated_by INT DEFAULT NULL, ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7F16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_427C1C7FDE12AB56 ON organizations (created_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_427C1C7F16FE72E1 ON organizations (updated_by)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9F1C37890');
        $this->addSql('DROP INDEX IDX_1483A5E9F1C37890 ON users');
        $this->addSql('ALTER TABLE users ADD created_by INT DEFAULT NULL, CHANGE organization_id_id updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E916FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9DE12AB56 ON users (created_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E916FE72E1 ON users (updated_by)');
    }
}
