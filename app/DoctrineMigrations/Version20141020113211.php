<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141020113211 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acid_detection_strips CHANGE organization_id organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE records CHANGE is_review is_review TINYINT(1) DEFAULT \'0\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acid_detection_strips CHANGE organization_id organization_id INT NOT NULL');
        $this->addSql('ALTER TABLE records CHANGE is_review is_review TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
