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
	private $manifestColumns = array('Unique ID', 'Institution', 'Collection Name', 'Format', 'Print Type',
		"Reel Diameter\nDisc Diameter\nCassette Size", 'Title', 'Approximate Duration');

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

}
