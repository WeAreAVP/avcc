<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141002151309 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organizations CHANGE department_name department_name VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE contact_person_name contact_person_name VARCHAR(255) DEFAULT NULL, CHANGE contact_person_email contact_person_email VARCHAR(255) DEFAULT NULL, CHANGE contact_person_phone contact_person_phone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organizations CHANGE department_name department_name VARCHAR(255) NOT NULL, CHANGE address address VARCHAR(255) NOT NULL, CHANGE contact_person_name contact_person_name VARCHAR(255) NOT NULL, CHANGE contact_person_email contact_person_email VARCHAR(255) NOT NULL, CHANGE contact_person_phone contact_person_phone VARCHAR(255) NOT NULL');
    }
}
