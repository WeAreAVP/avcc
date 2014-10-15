<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141015111451 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE acid_detection_strips (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE audio_records (id INT AUTO_INCREMENT NOT NULL, disk_diameter_id INT DEFAULT NULL, media_diameter_id INT DEFAULT NULL, base_id INT DEFAULT NULL, recording_speed_id INT DEFAULT NULL, tape_thickness_id INT DEFAULT NULL, side_id INT DEFAULT NULL, track_type_id INT DEFAULT NULL, mono_stero_id INT DEFAULT NULL, noice_reduction_id INT DEFAULT NULL, record_id INT NOT NULL, media_duration INT NOT NULL, INDEX IDX_A32A519A898CA4D7 (disk_diameter_id), INDEX IDX_A32A519A113F896F (media_diameter_id), INDEX IDX_A32A519A6967DF41 (base_id), INDEX IDX_A32A519A2C09D5DC (recording_speed_id), INDEX IDX_A32A519ABC2C1E65 (tape_thickness_id), INDEX IDX_A32A519A965D81C4 (side_id), INDEX IDX_A32A519A1CD2148E (track_type_id), INDEX IDX_A32A519A885ED7C5 (mono_stero_id), INDEX IDX_A32A519A68EA871 (noice_reduction_id), UNIQUE INDEX UNIQ_A32A519A4DFD750C (record_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commercial_unique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE film_records (id INT AUTO_INCREMENT NOT NULL, print_type_id INT DEFAULT NULL, reel_core_id INT DEFAULT NULL, base_id INT DEFAULT NULL, color_id INT DEFAULT NULL, sound_id INT DEFAULT NULL, frame_rate_id INT DEFAULT NULL, acid_detection_id INT DEFAULT NULL, record_id INT NOT NULL, footage INT NOT NULL, media_diameter INT NOT NULL, shrinkage DOUBLE PRECISION NOT NULL, INDEX IDX_7C234365E02F994D (print_type_id), INDEX IDX_7C234365C6AEFCDB (reel_core_id), INDEX IDX_7C2343656967DF41 (base_id), INDEX IDX_7C2343657ADA1FB5 (color_id), INDEX IDX_7C2343656AAA5C3E (sound_id), INDEX IDX_7C2343656757FF32 (frame_rate_id), INDEX IDX_7C23436571401907 (acid_detection_id), UNIQUE INDEX UNIQ_7C2343654DFD750C (record_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mono_stereo (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE print_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE records (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, user_id INT DEFAULT NULL, media_type_id INT DEFAULT NULL, format_id INT DEFAULT NULL, commercial_id INT DEFAULT NULL, reel_diameter_id INT DEFAULT NULL, created_on DATETIME NOT NULL, updated_on DATETIME DEFAULT NULL, unique_id VARCHAR(255) NOT NULL, collection_name VARCHAR(255) NOT NULL, content_duration INT NOT NULL, creation_date VARCHAR(255) NOT NULL, content_date VARCHAR(255) NOT NULL, is_review TINYINT(1) DEFAULT \'0\' NOT NULL, genre_terms VARCHAR(250) DEFAULT NULL, contributor VARCHAR(500) DEFAULT NULL, generation VARCHAR(500) DEFAULT NULL, part VARCHAR(250) DEFAULT NULL, copyright_restrictions VARCHAR(250) DEFAULT NULL, duplicates_derivatives VARCHAR(250) DEFAULT NULL, related_material VARCHAR(250) DEFAULT NULL, condition_note VARCHAR(500) DEFAULT NULL, INDEX IDX_9C9D5846166D1F9C (project_id), INDEX IDX_9C9D5846A76ED395 (user_id), INDEX IDX_9C9D5846A49B0ADA (media_type_id), INDEX IDX_9C9D5846D629F605 (format_id), INDEX IDX_9C9D58467854071C (commercial_id), INDEX IDX_9C9D58469ACC2BCB (reel_diameter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_records (id INT AUTO_INCREMENT NOT NULL, cassete_size_id INT DEFAULT NULL, format_version_id INT DEFAULT NULL, recording_speed_id INT DEFAULT NULL, recording_standard_id INT DEFAULT NULL, record_id INT NOT NULL, media_duration INT NOT NULL, INDEX IDX_7970B1EB519A4C2C (cassete_size_id), INDEX IDX_7970B1EB8831B088 (format_version_id), INDEX IDX_7970B1EB2C09D5DC (recording_speed_id), INDEX IDX_7970B1EB476CADD8 (recording_standard_id), UNIQUE INDEX UNIQ_7970B1EB4DFD750C (record_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A898CA4D7 FOREIGN KEY (disk_diameter_id) REFERENCES disk_diameters (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A113F896F FOREIGN KEY (media_diameter_id) REFERENCES media_diameters (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A6967DF41 FOREIGN KEY (base_id) REFERENCES bases (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A2C09D5DC FOREIGN KEY (recording_speed_id) REFERENCES recording_speed (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519ABC2C1E65 FOREIGN KEY (tape_thickness_id) REFERENCES tape_thickness (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A965D81C4 FOREIGN KEY (side_id) REFERENCES slides (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A1CD2148E FOREIGN KEY (track_type_id) REFERENCES track_types (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A885ED7C5 FOREIGN KEY (mono_stero_id) REFERENCES mono_stereo (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A68EA871 FOREIGN KEY (noice_reduction_id) REFERENCES noice_reduction (id)');
        $this->addSql('ALTER TABLE audio_records ADD CONSTRAINT FK_A32A519A4DFD750C FOREIGN KEY (record_id) REFERENCES records (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_records ADD CONSTRAINT FK_7C234365E02F994D FOREIGN KEY (print_type_id) REFERENCES print_types (id)');
        $this->addSql('ALTER TABLE film_records ADD CONSTRAINT FK_7C234365C6AEFCDB FOREIGN KEY (reel_core_id) REFERENCES reel_core (id)');
        $this->addSql('ALTER TABLE film_records ADD CONSTRAINT FK_7C2343656967DF41 FOREIGN KEY (base_id) REFERENCES bases (id)');
        $this->addSql('ALTER TABLE film_records ADD CONSTRAINT FK_7C2343657ADA1FB5 FOREIGN KEY (color_id) REFERENCES colors (id)');
        $this->addSql('ALTER TABLE film_records ADD CONSTRAINT FK_7C2343656AAA5C3E FOREIGN KEY (sound_id) REFERENCES sounds (id)');
        $this->addSql('ALTER TABLE film_records ADD CONSTRAINT FK_7C2343656757FF32 FOREIGN KEY (frame_rate_id) REFERENCES frame_rates (id)');
        $this->addSql('ALTER TABLE film_records ADD CONSTRAINT FK_7C23436571401907 FOREIGN KEY (acid_detection_id) REFERENCES acid_detection_strips (id)');
        $this->addSql('ALTER TABLE film_records ADD CONSTRAINT FK_7C2343654DFD750C FOREIGN KEY (record_id) REFERENCES records (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE records ADD CONSTRAINT FK_9C9D5846166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE records ADD CONSTRAINT FK_9C9D5846A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE records ADD CONSTRAINT FK_9C9D5846A49B0ADA FOREIGN KEY (media_type_id) REFERENCES media_types (id)');
        $this->addSql('ALTER TABLE records ADD CONSTRAINT FK_9C9D5846D629F605 FOREIGN KEY (format_id) REFERENCES formats (id)');
        $this->addSql('ALTER TABLE records ADD CONSTRAINT FK_9C9D58467854071C FOREIGN KEY (commercial_id) REFERENCES commercial_unique (id)');
        $this->addSql('ALTER TABLE records ADD CONSTRAINT FK_9C9D58469ACC2BCB FOREIGN KEY (reel_diameter_id) REFERENCES reel_diameters (id)');
        $this->addSql('ALTER TABLE video_records ADD CONSTRAINT FK_7970B1EB519A4C2C FOREIGN KEY (cassete_size_id) REFERENCES cassette_sizes (id)');
        $this->addSql('ALTER TABLE video_records ADD CONSTRAINT FK_7970B1EB8831B088 FOREIGN KEY (format_version_id) REFERENCES format_versions (id)');
        $this->addSql('ALTER TABLE video_records ADD CONSTRAINT FK_7970B1EB2C09D5DC FOREIGN KEY (recording_speed_id) REFERENCES recording_speed (id)');
        $this->addSql('ALTER TABLE video_records ADD CONSTRAINT FK_7970B1EB476CADD8 FOREIGN KEY (recording_standard_id) REFERENCES recording_standards (id)');
        $this->addSql('ALTER TABLE video_records ADD CONSTRAINT FK_7970B1EB4DFD750C FOREIGN KEY (record_id) REFERENCES records (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE acid_deduction_strips');
        $this->addSql('DROP TABLE mono_stero');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE film_records DROP FOREIGN KEY FK_7C23436571401907');
        $this->addSql('ALTER TABLE records DROP FOREIGN KEY FK_9C9D58467854071C');
        $this->addSql('ALTER TABLE audio_records DROP FOREIGN KEY FK_A32A519A885ED7C5');
        $this->addSql('ALTER TABLE film_records DROP FOREIGN KEY FK_7C234365E02F994D');
        $this->addSql('ALTER TABLE audio_records DROP FOREIGN KEY FK_A32A519A4DFD750C');
        $this->addSql('ALTER TABLE film_records DROP FOREIGN KEY FK_7C2343654DFD750C');
        $this->addSql('ALTER TABLE video_records DROP FOREIGN KEY FK_7970B1EB4DFD750C');
        $this->addSql('CREATE TABLE acid_deduction_strips (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mono_stero (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE acid_detection_strips');
        $this->addSql('DROP TABLE audio_records');
        $this->addSql('DROP TABLE commercial_unique');
        $this->addSql('DROP TABLE film_records');
        $this->addSql('DROP TABLE mono_stereo');
        $this->addSql('DROP TABLE print_types');
        $this->addSql('DROP TABLE records');
        $this->addSql('DROP TABLE video_records');
    }
}
