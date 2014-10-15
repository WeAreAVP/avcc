<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141015131638 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE colors ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE colors ADD CONSTRAINT FK_C2BEC39F32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C2BEC39F32C8A3DE ON colors (organization_id)');
        $this->addSql('ALTER TABLE commercial_unique ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commercial_unique ADD CONSTRAINT FK_FCE4382B32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FCE4382B32C8A3DE ON commercial_unique (organization_id)');
        $this->addSql('ALTER TABLE disk_diameters ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE disk_diameters ADD CONSTRAINT FK_DD84FBDC32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_DD84FBDC32C8A3DE ON disk_diameters (organization_id)');
        $this->addSql('ALTER TABLE format_versions ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE format_versions ADD CONSTRAINT FK_BEE6700632C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BEE6700632C8A3DE ON format_versions (organization_id)');
        $this->addSql('ALTER TABLE frame_rates ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE frame_rates ADD CONSTRAINT FK_870C1DFE32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_870C1DFE32C8A3DE ON frame_rates (organization_id)');
        $this->addSql('ALTER TABLE media_diameters ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_diameters ADD CONSTRAINT FK_BB7F6A0232C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BB7F6A0232C8A3DE ON media_diameters (organization_id)');
        $this->addSql('ALTER TABLE mono_stereo ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mono_stereo ADD CONSTRAINT FK_8732251532C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_8732251532C8A3DE ON mono_stereo (organization_id)');
        $this->addSql('ALTER TABLE noice_reduction ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE noice_reduction ADD CONSTRAINT FK_4A0FAC9932C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4A0FAC9932C8A3DE ON noice_reduction (organization_id)');
        $this->addSql('ALTER TABLE print_types ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE print_types ADD CONSTRAINT FK_285503FB32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_285503FB32C8A3DE ON print_types (organization_id)');
        $this->addSql('ALTER TABLE recording_speed ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recording_speed ADD CONSTRAINT FK_A9A6C79B32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A9A6C79B32C8A3DE ON recording_speed (organization_id)');
        $this->addSql('ALTER TABLE recording_standards ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recording_standards ADD CONSTRAINT FK_8E02410832C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_8E02410832C8A3DE ON recording_standards (organization_id)');
        $this->addSql('ALTER TABLE reel_core ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reel_core ADD CONSTRAINT FK_F665FF0832C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F665FF0832C8A3DE ON reel_core (organization_id)');
        $this->addSql('ALTER TABLE reel_diameters ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reel_diameters ADD CONSTRAINT FK_1350686232C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_1350686232C8A3DE ON reel_diameters (organization_id)');
        $this->addSql('ALTER TABLE slides ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slides ADD CONSTRAINT FK_B8C0209132C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B8C0209132C8A3DE ON slides (organization_id)');
        $this->addSql('ALTER TABLE sounds ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sounds ADD CONSTRAINT FK_F12306F132C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F12306F132C8A3DE ON sounds (organization_id)');
        $this->addSql('ALTER TABLE tape_thickness ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tape_thickness ADD CONSTRAINT FK_34DDB80832C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_34DDB80832C8A3DE ON tape_thickness (organization_id)');
        $this->addSql('ALTER TABLE track_types ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE track_types ADD CONSTRAINT FK_29C72B9732C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_29C72B9732C8A3DE ON track_types (organization_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE colors DROP FOREIGN KEY FK_C2BEC39F32C8A3DE');
        $this->addSql('DROP INDEX IDX_C2BEC39F32C8A3DE ON colors');
        $this->addSql('ALTER TABLE colors DROP organization_id');
        $this->addSql('ALTER TABLE commercial_unique DROP FOREIGN KEY FK_FCE4382B32C8A3DE');
        $this->addSql('DROP INDEX IDX_FCE4382B32C8A3DE ON commercial_unique');
        $this->addSql('ALTER TABLE commercial_unique DROP organization_id');
        $this->addSql('ALTER TABLE disk_diameters DROP FOREIGN KEY FK_DD84FBDC32C8A3DE');
        $this->addSql('DROP INDEX IDX_DD84FBDC32C8A3DE ON disk_diameters');
        $this->addSql('ALTER TABLE disk_diameters DROP organization_id');
        $this->addSql('ALTER TABLE format_versions DROP FOREIGN KEY FK_BEE6700632C8A3DE');
        $this->addSql('DROP INDEX IDX_BEE6700632C8A3DE ON format_versions');
        $this->addSql('ALTER TABLE format_versions DROP organization_id');
        $this->addSql('ALTER TABLE frame_rates DROP FOREIGN KEY FK_870C1DFE32C8A3DE');
        $this->addSql('DROP INDEX IDX_870C1DFE32C8A3DE ON frame_rates');
        $this->addSql('ALTER TABLE frame_rates DROP organization_id');
        $this->addSql('ALTER TABLE media_diameters DROP FOREIGN KEY FK_BB7F6A0232C8A3DE');
        $this->addSql('DROP INDEX IDX_BB7F6A0232C8A3DE ON media_diameters');
        $this->addSql('ALTER TABLE media_diameters DROP organization_id');
        $this->addSql('ALTER TABLE mono_stereo DROP FOREIGN KEY FK_8732251532C8A3DE');
        $this->addSql('DROP INDEX IDX_8732251532C8A3DE ON mono_stereo');
        $this->addSql('ALTER TABLE mono_stereo DROP organization_id');
        $this->addSql('ALTER TABLE noice_reduction DROP FOREIGN KEY FK_4A0FAC9932C8A3DE');
        $this->addSql('DROP INDEX IDX_4A0FAC9932C8A3DE ON noice_reduction');
        $this->addSql('ALTER TABLE noice_reduction DROP organization_id');
        $this->addSql('ALTER TABLE print_types DROP FOREIGN KEY FK_285503FB32C8A3DE');
        $this->addSql('DROP INDEX IDX_285503FB32C8A3DE ON print_types');
        $this->addSql('ALTER TABLE print_types DROP organization_id');
        $this->addSql('ALTER TABLE recording_speed DROP FOREIGN KEY FK_A9A6C79B32C8A3DE');
        $this->addSql('DROP INDEX IDX_A9A6C79B32C8A3DE ON recording_speed');
        $this->addSql('ALTER TABLE recording_speed DROP organization_id');
        $this->addSql('ALTER TABLE recording_standards DROP FOREIGN KEY FK_8E02410832C8A3DE');
        $this->addSql('DROP INDEX IDX_8E02410832C8A3DE ON recording_standards');
        $this->addSql('ALTER TABLE recording_standards DROP organization_id');
        $this->addSql('ALTER TABLE reel_core DROP FOREIGN KEY FK_F665FF0832C8A3DE');
        $this->addSql('DROP INDEX IDX_F665FF0832C8A3DE ON reel_core');
        $this->addSql('ALTER TABLE reel_core DROP organization_id');
        $this->addSql('ALTER TABLE reel_diameters DROP FOREIGN KEY FK_1350686232C8A3DE');
        $this->addSql('DROP INDEX IDX_1350686232C8A3DE ON reel_diameters');
        $this->addSql('ALTER TABLE reel_diameters DROP organization_id');
        $this->addSql('ALTER TABLE slides DROP FOREIGN KEY FK_B8C0209132C8A3DE');
        $this->addSql('DROP INDEX IDX_B8C0209132C8A3DE ON slides');
        $this->addSql('ALTER TABLE slides DROP organization_id');
        $this->addSql('ALTER TABLE sounds DROP FOREIGN KEY FK_F12306F132C8A3DE');
        $this->addSql('DROP INDEX IDX_F12306F132C8A3DE ON sounds');
        $this->addSql('ALTER TABLE sounds DROP organization_id');
        $this->addSql('ALTER TABLE tape_thickness DROP FOREIGN KEY FK_34DDB80832C8A3DE');
        $this->addSql('DROP INDEX IDX_34DDB80832C8A3DE ON tape_thickness');
        $this->addSql('ALTER TABLE tape_thickness DROP organization_id');
        $this->addSql('ALTER TABLE track_types DROP FOREIGN KEY FK_29C72B9732C8A3DE');
        $this->addSql('DROP INDEX IDX_29C72B9732C8A3DE ON track_types');
        $this->addSql('ALTER TABLE track_types DROP organization_id');
    }
}
