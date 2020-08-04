<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@weareavp.com>
 * @author   Rimsha Khalid <rimsha@weareavp.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.weareavp.com
 */

namespace Application\Bundle\FrontBundle\Helper;

class ExportFields {

    private $columns = array(
        'Project_Name',
        'Collection_Classification',
        'Collection_Name',
        'Media_Type',
        'Unique_ID',
        'Alternate_ID',
        'Location',
        'Format',
        'Title',
        'Description',
        'Commercial_or_Unique',
        'Content_Duration',
        'Media_Duration',
        'Creation_Date',
        'Content_Date',
        'Base',
        'Print_Type',
        'Disk_Diameter',
        'Reel_Diameter',
        'Media_Diameter',
        'Footage',
        'Recording_Speed',
        'Color',
        'Tape_Thickness',
        'Sides',
        'Track_Type',
        'Mono_or_Stereo',
        'Noise_Reduction',
        'Cassette_Size',
        'Format_Version',
        'Recording_Standard',
        'Reel_or_Core',
        'Sound',
        'Edge_Code/_Year',
        'Frame_Rate',
        'Acid_Detection_Strip',
        'Shrinkage',
        'Genre_Terms',
        'Contributor',
        'Generation',
        'Part',
        'Copyright_/_Restrictions',
        'Duplicates_/_Derivatives',
        'Related_Material',
        'Condition_Note',
        'General_Note',
        'Manager_Review',
        'Reformatting_Priority',
        'Transcription',
        'Digitized',
        'Digitized_By',
        'Digitized_When',
        'URN',
        'Access_Level',
        'Time_Stamp',
        'Timestamp_-_Last_Change',
        'Cataloger'    
    );
    private $mergeColumns = array(
        'Ext_Project_Name',
        'Ext_Collection_Classification',
        'Ext_Collection_Name',
        'Ext_Media_Type',
        'Ext_Unique_ID',
        'Ext_Alternate_ID',
        'Ext_Location',
        'Ext_Format',
        'Ext_Title',
        'Ext_Description',
        'Ext_Commercial_or_Unique',
        'Ext_Content_Duration',
        'Ext_Media_Duration',
        'Ext_Creation_Date',
        'Ext_Content_Date',
        'Ext_Base',
        'Ext_Print_Type',
        'Ext_Disk_Diameter',
        'Ext_Reel_Diameter',
        'Ext_Media_Diameter',
        'Ext_Footage',
        'Ext_Recording_Speed',
        'Ext_Color',
        'Ext_Tape_Thickness',
        'Ext_Sides',
        'Ext_Track_Type',
        'Ext_Mono_or_Stereo',
        'Ext_Noise_Reduction',
        'Ext_Cassette_Size',
        'Ext_Format_Version',
        'Ext_Recording_Standard',
        'Ext_Reel_or_Core',
        'Ext_Sound',
        'Ext_Edge_Code/_Year',
        'Ext_Frame_Rate',
        'Ext_Acid_Detection_Strip',
        'Ext_Shrinkage',
        'Ext_Genre_Terms',
        'Ext_Contributor',
        'Ext_Generation',
        'Ext_Part',
        'Ext_Copyright_/_Restrictions',
        'Ext_Duplicates_/_Derivatives',
        'Ext_Related_Material',
        'Ext_Condition_Note',
        'Ext_General_Note',
        'Ext_Manager_Review',
        'Ext_Reformatting_Priority',
        'Ext_Transcription',
        'Ext_Digitized',
        'Ext_Digitized_By',
        'Ext_Digitized_When',
        'Ext_URN',
        'Ext_Access_Level',
        'Ext_Time_Stamp',
        'Ext_Timestamp_-_Last_Change',
        'Ext_Cataloger'
    );
    private $manifestColumns = array('Unique ID', 'Institution', 'Collection Name', 'Format', 'Print Type',
        "Reel Diameter\nDisc Diameter\nCassette Size", 'Title', 'Approximate Duration');
    private $prioritizationCols = array('Project_Name', 'Collection_Name', 'Title', 'Unique_ID', 'Total Score');
    private $fileSizeCalculatorColumns = array(
        'audio' => array(
            'Media Type',
            'Format',
            'Count',
            'Total Duration',
            'Average Duration',
            '96/24 Uncompressed WAV Stereo (TB)',
            '48/24 Uncompressed WAV Stereo (TB)',
            '48/16 Uncompressed WAV Stereo (TB)',
            '44.1/16 Uncompressed WAV Stereo (TB)',
            '96/24 Uncompressed WAV Mono (TB)',
            '48/24 Uncompressed WAV Mono (TB)',
            '48/16 Uncompressed WAV Mono (TB)',
            '44.1/16 Uncompressed WAV Mono (TB)',
            '256Kbps MP3',
        ),
        'video' => array(
            'Media Type',
            'Format',
            'Count',
            'Total Duration',
            'Average Duration',
            'Uncompressed 10-bit .mov HD (TB)',
            'Uncompressed 10-bit .mov SD (TB)',
            'Lossless compression 10-bit JP2k (TB)',
            'FFV1 10-bit (TB)',
            'MPEG2 8-bit (TB)',
            'ProRes 422 (TB)',
            'DV25 (TB)',
            'MPEG4 5.0Mbps (TB)',
            'MPEG4 2.0Mbps (TB)',
        ),
        'film' => array(
            'Media Type',
            'Format',
            'Count',
            'Total Duration',
            'Average Duration',
            '4k Uncompressed (TB)',
            '4k Lossless compression (TB)',
            '2k Uncompressed (TB)',
            '2k Lossless compression (TB)',
            'AVC Intra 100 (TB)',
            'MPEG4 5.0Mbps (TB)',
            'MPEG4 2.0Mbps (TB)',
        )
    );
    private $linearFootCalculatorColumns = array('Media Type', 'Format', 'Width', 'Total Count', 'Linear Feet');

    /**
     * Return array of columns for csv or xlsx tempate.
     *
     * @return array
     */
    public function getExportColumns() {
        return $this->columns;
    }

    /**
     * Return array of manifest columns for xlsx tempate.
     *
     * @return array
     */
    public function getManifestColumns() {
        return $this->manifestColumns;
    }

    /**
     * Return array of Prioritization Columns for xlsx tempate.
     *
     * @return array
     */
    public function getPrioritizationColumns() {
        return $this->prioritizationCols;
    }

    /**
     * Return array of columns for csv or xlsx tempate.
     *
     * @return array
     */
    public function getExportMergeColumns() {
        return $this->mergeColumns;
    }

    /**
     * Return array of file size calculator columns for csv or xlsx tempate.
     *
     * @return array
     */
    public function getFileSizeCalculatorColumns() {
        return $this->fileSizeCalculatorColumns;
    }

    /**
     * Return array of linear foot calculator columns for csv or xlsx tempate.
     *
     * @return array
     */
    public function getLinearFootCalculatorColumns() {
        return $this->linearFootCalculatorColumns;
    }

}
