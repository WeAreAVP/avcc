<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141014094516 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE disk_diameters (id INT AUTO_INCREMENT NOT NULL, format_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_DD84FBDCD629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_diameters (id INT AUTO_INCREMENT NOT NULL, format_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_BB7F6A02D629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reel_diameters (id INT AUTO_INCREMENT NOT NULL, format_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_13506862D629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE disk_diameters ADD CONSTRAINT FK_DD84FBDCD629F605 FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_diameters ADD CONSTRAINT FK_BB7F6A02D629F605 FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reel_diameters ADD CONSTRAINT FK_13506862D629F605 FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('DROP TABLE disk_diameters');
        $this->addSql('DROP TABLE media_diameters');
        $this->addSql('DROP TABLE reel_diameters');
    }
}
