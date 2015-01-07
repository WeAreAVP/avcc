<?php

namespace Application\Bundle\FrontBundle\Helper;

class ExportFields
{

    private $columns = array(
        'Project_Name',
        'Collection_Name',
        'Media_Type',
        'Unique_ID',
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
        'Time_Stamp',
        'Timestamp_-_Last_Change',
        'Cataloger'
    );
    private $mergeColumns = array(
        'Ext_Project_Name',
        'Ext_Collection_Name',
        'Ext_Media_Type',
        'Ext_Unique_ID',
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
            '96/24 Uncompressed WAV Stereo',
            '48/24 Uncompressed WAV Stereo',
            '48/16 Uncompressed WAV Stereo',
            '44.1/16 Uncompressed WAV Stereo',
            '96/24 Uncompressed WAV Mono',
            '48/24 Uncompressed WAV Mono',
            '48/16 Uncompressed WAV Mono',
            '44.1/16 Uncompressed WAV Mono',
            '256Kbps MP3',
        ),
        'video' => array(
            'Media Type',
            'Format',
            'Count',
            'Total Duration',
            'Average Duration',
            'Uncompressed 10-bit .mov HD',
            'Uncompressed 10-bit .mov SD',
            'Lossless compression 10-bit JP2k',
            'FFV1 10-bit',
            'MPEG2 8-bit',
            'ProRes 422',
            'DV25',
            'MPEG4 5.0Mbps',
            'MPEG4 2.0Mbps',
        )
    );

    /**
     * Return array of columns for csv or xlsx tempate.
     *
     * @return array
     */
    public function getExportColumns()
    {
        return $this->columns;
    }

    /**
     * Return array of manifest columns for xlsx tempate.
     *
     * @return array
     */
    public function getManifestColumns()
    {
        return $this->manifestColumns;
    }

    /**
     * Return array of Prioritization Columns for xlsx tempate.
     *
     * @return array
     */
    public function getPrioritizationColumns()
    {
        return $this->prioritizationCols;
    }

    /**
     * Return array of columns for csv or xlsx tempate.
     *
     * @return array
     */
    public function getExportMergeColumns()
    {
        return $this->mergeColumns;
    }

    /**
     * Return array of file size calculator columns for csv or xlsx tempate.
     *
     * @return array
     */
    public function getFileSizeCalculatorColumns()
    {
        return $this->fileSizeCalculatorColumns;
    }

}
