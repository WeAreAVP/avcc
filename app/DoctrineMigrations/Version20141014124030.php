<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141014124030 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE acid_deduction_strips (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cassette_sizes (id INT AUTO_INCREMENT NOT NULL, format_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_2007DC8BD629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE colors (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE format_versions (id INT AUTO_INCREMENT NOT NULL, format_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_BEE67006D629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frame_rates (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mono_stero (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noice_reduction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recording_speed (id INT AUTO_INCREMENT NOT NULL, format_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_A9A6C79BD629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recording_standards (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reel_core (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slides (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sounds (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tape_thickness (id INT AUTO_INCREMENT NOT NULL, format_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_34DDB808D629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE track_types (id INT AUTO_INCREMENT NOT NULL, format_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_29C72B97D629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cassette_sizes ADD CONSTRAINT FK_2007DC8BD629F605 FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE format_versions ADD CONSTRAINT FK_BEE67006D629F605 FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recording_speed ADD CONSTRAINT FK_A9A6C79BD629F605 FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tape_thickness ADD CONSTRAINT FK_34DDB808D629F605 FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE track_types ADD CONSTRAINT FK_29C72B97D629F605 FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE acid_deduction_strips');
        $this->addSql('DROP TABLE cassette_sizes');
        $this->addSql('DROP TABLE colors');
        $this->addSql('DROP TABLE format_versions');
        $this->addSql('DROP TABLE frame_rates');
        $this->addSql('DROP TABLE mono_stero');
        $this->addSql('DROP TABLE noice_reduction');
        $this->addSql('DROP TABLE recording_speed');
        $this->addSql('DROP TABLE recording_standards');
        $this->addSql('DROP TABLE reel_core');
        $this->addSql('DROP TABLE slides');
        $this->addSql('DROP TABLE sounds');
        $this->addSql('DROP TABLE tape_thickness');
        $this->addSql('DROP TABLE track_types');
    }
}
