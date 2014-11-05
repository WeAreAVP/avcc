<?php

namespace Application\Bundle\FrontBundle\Helper;

class DefaultFields
{

    private $defaultOrder = array();

    function __construct()
    {
    }

    function getDefaultOrder()
    {
        $this->defaultOrder['audio'] = array(
            "Media_Type" => array("title" => 'Media Type', 'field' => "record.mediaType", "is_required" => 1, "hidden" => 0),
            "Project_Name" => array("title" => 'Project Name', 'field' => "record.project", "is_required" => 1, "hidden" => 0),
            "Unique_Id" => array("title" => 'Unique Id', 'field' => "record.uniqueId", "is_required" => 1, "hidden" => 0),
            "Location" => array("title" => 'Location', 'field' => "record.location", "is_required" => 1, "hidden" => 0),
            "Format" => array("title" => 'Format', 'field' => "record.format", "is_required" => 1, "hidden" => 0),
            "Title" => array("title" => 'Title', 'field' => "record.title", "is_required" => 1, "hidden" => 0),
            "Collection_Name" => array("title" => 'Collection Name', 'field' => "record.collectionName", "is_required" => 1, "hidden" => 0),
            "Description" => array("title" => 'Description', 'field' => "record.description", "is_required" => 0, "hidden" => 0),
            "Commercial" => array("title" => 'Commercial', 'field' => "record.commercial", "is_required" => 0, "hidden" => 0),
            "Disk_Diameter" => array("title" => 'Disk Diameter', 'field' => "diskDiameters", "is_required" => 0, "hidden" => 0),
            "Reel_Diameter" => array("title" => 'Reel Diameter', 'field' => "record.reelDiameters", "is_required" => 0, "hidden" => 0),
            "Media_Diameter" => array("title" => 'Media Diameter', 'field' => "mediaDiameters", "is_required" => 0, "hidden" => 0),
            "Base" => array("title" => 'Base', 'field' => "bases", "is_required" => 0, "hidden" => 0),
            "Content_Duration" => array("title" => 'Content Duration', 'field' => "record.contentDuration", "is_required" => 0, "hidden" => 0),
            "Media_Duration" => array("title" => 'Media Duration', 'field' => "mediaDuration", "is_required" => 0, "hidden" => 0),
            "Creation_Date" => array("title" => 'Creation Date', 'field' => "record.creationDate", "is_required" => 0, "hidden" => 0),
            "Content_Data" => array("title" => 'Content Data', 'field' => "record.contentDate", "is_required" => 0, "hidden" => 0),
            "Review" => array("title" => 'Review', 'field' => "record.isReview", "is_required" => 0, "hidden" => 0),
            "Recording_Speed" => array("title" => 'Recording Speed', 'field' => "recordingSpeed", "is_required" => 0, "hidden" => 0),
            "Tape_Thickness" => array("title" => 'Tape Thickness', 'field' => "tapeThickness", "is_required" => 0, "hidden" => 0),
            "Slides" => array("title" => 'Slides', 'field' => "slides", "is_required" => 0, "hidden" => 0),
            "Track_Type" => array("title" => 'Track Type', 'field' => "trackTypes", "is_required" => 0, "hidden" => 0),
            "Mono_Stereo" => array("title" => 'Mono or Stereo', 'field' => "monoStereo", "is_required" => 0, "hidden" => 0),
            "Noice_Reduction" => array("title" => 'Noice Reduction', 'field' => "noiceReduction", "is_required" => 0, "hidden" => 0),
            "Gener_Terms" => array("title" => 'Description', 'field' => "record.genreTerms", "is_required" => 0, "hidden" => 0),
            "Contributor" => array("title" => 'Contributor', 'field' => "record.contributor", "is_required" => 0, "hidden" => 0),
            "Gerneration" => array("title" => 'Gerneration', 'field' => "record.generation", "is_required" => 0, "hidden" => 0),
            "Part" => array("title" => 'Part', 'field' => "record.part", "is_required" => 0, "hidden" => 0),
            "Copyrights" => array("title" => 'Copyrights', 'field' => "record.copyrightRestrictions", "is_required" => 0, "hidden" => 0),
            "Duplicates" => array("title" => 'Duplicates', 'field' => "record.duplicatesDerivatives", "is_required" => 0, "hidden" => 0),
            "Related_Meterial" => array("title" => 'Related Meterial', 'field' => "record.relatedMaterial", "is_required" => 0, "hidden" => 0),
            "Condition_Note" => array("title" => 'Condition Note', 'field' => "record.conditionNote", "is_required" => 0, "hidden" => 0)
        );

        $this->defaultOrder['video'] = array(
            "Media_Type" => array("title" => 'Media Type', 'field' => "record.mediaType", "is_required" => 1, "hidden" => 0),
            "Project_Name" => array("title" => 'Project Name', 'field' => "record.project", "is_required" => 1, "hidden" => 0),
            "Unique_Id" => array("title" => 'Unique Id', 'field' => "record.uniqueId", "is_required" => 1, "hidden" => 0),
            "Location" => array("title" => 'Location', 'field' => "record.location", "is_required" => 1, "hidden" => 0),
            "Format" => array("title" => 'Format', 'field' => "record.format", "is_required" => 1, "hidden" => 0),
            "Title" => array("title" => 'Title', 'field' => "record.title", "is_required" => 1, "hidden" => 0),
            "Collection_Name" => array("title" => 'Collection Name', 'field' => "record.collectionName", "is_required" => 1, "hidden" => 0),
            "Description" => array("title" => 'Description', 'field' => "record.description", "is_required" => 0, "hidden" => 0),
            "Commercial" => array("title" => 'Commercial', 'field' => "record.commercial", "is_required" => 0, "hidden" => 0),
            "Cassette_Size" => array("title" => 'Cassette Size', 'field' => "cassetteSizes", "is_required" => 0, "hidden" => 0),
            "Reel_Diameter" => array("title" => 'Reel Diameter', 'field' => "record.reelDiameters", "is_required" => 0, "hidden" => 0),
            "Content_Duration" => array("title" => 'Content Duration', 'field' => "record.contentDuration", "is_required" => 0, "hidden" => 0),
            "Media_Duration" => array("title" => 'Media Duration', 'field' => "mediaDuration", "is_required" => 0, "hidden" => 0),
            "Creation_Date" => array("title" => 'Creation Date', 'field' => "record.creationDate", "is_required" => 0, "hidden" => 0),
            "Content_Data" => array("title" => 'Content Data', 'field' => "record.contentDate", "is_required" => 0, "hidden" => 0),
            "Review" => array("title" => 'Review', 'field' => "record.isReview", "is_required" => 0, "hidden" => 0),
            "Format_Version" => array("title" => 'Format Version', 'field' => "formatVersions", "is_required" => 0, "hidden" => 0),
            "Recording_Speed" => array("title" => 'Recording Speed', 'field' => "recordingSpeed", "is_required" => 0, "hidden" => 0),
            "Recording_Standard" => array("title" => 'Recording Standards', 'field' => "recordingStandards", "is_required" => 0, "hidden" => 0),
            "Gener_Terms" => array("title" => 'Description', 'field' => "record.genreTerms", "is_required" => 0, "hidden" => 0),
            "Contributor" => array("title" => 'Contributor', 'field' => "record.contributor", "is_required" => 0, "hidden" => 0),
            "Gerneration" => array("title" => 'Gerneration', 'field' => "record.generation", "is_required" => 0, "hidden" => 0),
            "Part" => array("title" => 'Part', 'field' => "record.part", "is_required" => 0, "hidden" => 0),
            "Copyrights" => array("title" => 'Copyrights', 'field' => "record.copyrightRestrictions", "is_required" => 0, "hidden" => 0),
            "Duplicates" => array("title" => 'Duplicates', 'field' => "record.duplicatesDerivatives", "is_required" => 0, "hidden" => 0),
            "Related_Meterial" => array("title" => 'Related Meterial', 'field' => "record.relatedMaterial", "is_required" => 0, "hidden" => 0),
            "Condition_Note" => array("title" => 'Condition Note', 'field' => "record.conditionNote", "is_required" => 0, "hidden" => 0)
        );
        
        $this->defaultOrder['film'] = array(
            "Media_Type" => array("title" => 'Media Type', 'field' => "record.mediaType", "is_required" => 1, "hidden" => 0),
            "Project_Name" => array("title" => 'Project Name', 'field' => "record.project", "is_required" => 1, "hidden" => 0),
            "Unique_Id" => array("title" => 'Unique Id', 'field' => "record.uniqueId", "is_required" => 1, "hidden" => 0),
            "Location" => array("title" => 'Location', 'field' => "record.location", "is_required" => 1, "hidden" => 0),
            "Format" => array("title" => 'Format', 'field' => "record.format", "is_required" => 1, "hidden" => 0),
            "Print_Type" => array("title" => 'Print Type', 'field' => "printTypes", "is_required" => 1, "hidden" => 0),
            "Title" => array("title" => 'Title', 'field' => "record.title", "is_required" => 1, "hidden" => 0),
            "Collection_Name" => array("title" => 'Collection Name', 'field' => "record.collectionName", "is_required" => 1, "hidden" => 0),
            "Description" => array("title" => 'Description', 'field' => "record.description", "is_required" => 0, "hidden" => 0),
            "Commercial" => array("title" => 'Commercial', 'field' => "record.commercial", "is_required" => 0, "hidden" => 0),
            "Reel_Core" => array("title" => 'Reel or Core', 'field' => "reelCore", "is_required" => 0, "hidden" => 0),            
            "Reel_Diameter" => array("title" => 'Reel Diameter', 'field' => "record.reelDiameters", "is_required" => 0, "hidden" => 0),
            "Footage" => array("title" => 'Footage', 'field' => "footage", "is_required" => 0, "hidden" => 0),
            "Media_Diameter" => array("title" => 'Media Diameter', 'field' => "mediaDiameters", "is_required" => 0, "hidden" => 0),
            "Base" => array("title" => 'Base', 'field' => "bases", "is_required" => 0, "hidden" => 0),
            "Color" => array("title" => 'Color', 'field' => "colors", "is_required" => 0, "hidden" => 0),
            "Sound" => array("title" => 'Sound', 'field' => "sounds", "is_required" => 0, "hidden" => 0),
            "Content_Duration" => array("title" => 'Content Duration', 'field' => "record.contentDuration", "is_required" => 0, "hidden" => 0),
            "Creation_Date" => array("title" => 'Creation Date', 'field' => "record.creationDate", "is_required" => 0, "hidden" => 0),
            "Content_Date" => array("title" => 'Content Data', 'field' => "record.contentDate", "is_required" => 0, "hidden" => 0),
            "Review" => array("title" => 'Review', 'field' => "record.isReview", "is_required" => 0, "hidden" => 0),
            "Frame_Rate" => array("title" => 'Recording Speed', 'field' => "recordingSpeed", "is_required" => 0, "hidden" => 0),
            "Acid_Detection_Strip" => array("title" => 'Acid Detection Strip', 'field' => "acidDetectionStrip", "is_required" => 0, "hidden" => 0),
            "Shrinkage" => array("title" => 'Shrinkage', 'field' => "shrinkage", "is_required" => 0, "hidden" => 0),
            "Gener_Terms" => array("title" => 'Description', 'field' => "record.genreTerms", "is_required" => 0, "hidden" => 0),
            "Contributor" => array("title" => 'Contributor', 'field' => "record.contributor", "is_required" => 0, "hidden" => 0),
            "Gerneration" => array("title" => 'Gerneration', 'field' => "record.generation", "is_required" => 0, "hidden" => 0),
            "Part" => array("title" => 'Part', 'field' => "record.part", "is_required" => 0, "hidden" => 0),
            "Copyrights" => array("title" => 'Copyrights', 'field' => "record.copyrightRestrictions", "is_required" => 0, "hidden" => 0),
            "Duplicates" => array("title" => 'Duplicates', 'field' => "record.duplicatesDerivatives", "is_required" => 0, "hidden" => 0),
            "Related_Meterial" => array("title" => 'Related Meterial', 'field' => "record.relatedMaterial", "is_required" => 0, "hidden" => 0),
            "Condition_Note" => array("title" => 'Condition Note', 'field' => "record.conditionNote", "is_required" => 0, "hidden" => 0)
        );
        
        return json_encode($this->defaultOrder);
    }

}
