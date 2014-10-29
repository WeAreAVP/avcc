<?php

namespace Application\Bundle\FrontBundle\Helper;

class DefaultFields
{

    private $defaultOrder = array();

    function __construct()
    {

//        video=> {Unique_Id=> record.uniqueId, Location=> record.location, Format=> record.format, Title=> record.title, Collection_Name=> record.collectionName, Description=> record.description, Commercial=> record.commercial, Cassette_Size=> video.caseetteSizes, Reel_Diameter=> record.reelDiameter, Content_Duration=> record.contentDuration, Media_Duration=> video.mediaDuration, Creation_Date=> record.creationDate,Content_Data=> record.contentDate, Review=> record.review, Recording_Speed=> video.recordingSpeed, Format_Versions=> video.formatVersions, Recording_Standard=> video.recordingStandards, Gener_Terms=> records.genreTerms, Contributor=> records.contributor, Gerneration=> records.generation, Part=> records.part, Copyrights=> records.copyrightRestrictions, Duplicates=> records.duplicatesDerivatives, Related_Meterial=> records.relatedmeterial, Condition_Note=> records.conditionNote }
//        film=> {Unique_Id=> record.uniqueId, Location=> record.location, Format=> record.format, Print_Type=> film.printTypes, Title=> record.title, Collection_Name=> record.collectionName, Description=> record.description, Commercial=> record.commercial, Reel_Core=> film.reelCore, Reel_Diameter=> record.reelDiameter, Footage=> film.footage, Media_Diameter=> film.medidDiameter, Base=> film.bases, Color=> film.colors, Sound=> film.sounds, Content_Duration=> record.contentDuration, Creation_Date=> record.creationDate,Content_Data=> record.contentDate, Review=> record.review, Frame_Rate=> film.frameRates, Acid_detection_strip=> film.acidDetectionStrips, Shrinkage=> film.shrinkage, Gener_Terms=> records.genreTerms, Contributor=> records.contributor, Gerneration=> records.generation, Part=> records.part, Copyrights=> records.copyrightRestrictions, Duplicates=> records.duplicatesDerivatives, Related_Meterial=> records.relatedmeterial, Condition_Note=> records.conditionNote  }   
    }

    function getDefaultOrder()
    {
        $this->defaultOrder['audio'] = array(
            "Unique_Id" => array("title" => 'Unique Id', 'field' => "record.uniqueId", "is_required" => 1, "hidden" => 0),
            "Location" => array("title" => 'Location', 'field' => "record.location", "is_required" => 1, "hidden" => 0),
            "Format" => array("title" => 'Format', 'field' => "record.format", "is_required" => 1, "hidden" => 0),
            "Title" => array("title" => 'Title', 'field' => "record.title", "is_required" => 1, "hidden" => 0),
            "Collection_Name" => array("title" => 'Collection Name', 'field' => "record.collectionName", "is_required" => 1, "hidden" => 0),
            "Description" => array("title" => 'Description', 'field' => "record.description", "is_required" => 0, "hidden" => 0),
            "Commercial" => array("title" => 'Commercial', 'field' => "record.commercial", "is_required" => 0, "hidden" => 0),
            "Disk_Diameter" => array("title" => 'Disk Diameter', 'field' => "diskDiameters", "is_required" => 0, "hidden" => 0),
            "Reel_Diameter" => array("title" => 'DescrReel Diameteription', 'field' => "record.reelDiameters", "is_required" => 0, "hidden" => 0),
            "Media_Diameter" => array("title" => 'Media Diameter', 'field' => "medidDiameters", "is_required" => 0, "hidden" => 0),
            "Base" => array("title" => 'Base', 'field' => "bases", "is_required" => 0, "hidden" => 0),
            "Content_Duration" => array("title" => 'Content Duration', 'field' => "record.contentDuration", "is_required" => 0, "hidden" => 0),
            "Media_Duration" => array("title" => 'Media Duration', 'field' => "mediaDuration", "is_required" => 0, "hidden" => 0),
            "Creation_Date" => array("title" => 'Creation Date', 'field' => "record.creationDate", "is_required" => 0, "hidden" => 0),
            "Content_Data" => array("title" => 'Content Data', 'field' => "record.contentDate", "is_required" => 0, "hidden" => 0),
            "Review" => array("title" => 'Review', 'field' => "record.review", "is_required" => 0, "hidden" => 0),
            "Recording_Speed" => array("title" => 'Recording Speed', 'field' => "recordingSpeed", "is_required" => 0, "hidden" => 0),
            "Tape_Thickness" => array("title" => 'Tape Thickness', 'field' => "tapeThickness", "is_required" => 0, "hidden" => 0),
            "Slides" => array("title" => 'Slides', 'field' => "slides", "is_required" => 0, "hidden" => 0),
            "Track_Type" => array("title" => 'Track Type', 'field' => "trackTypes", "is_required" => 0, "hidden" => 0),
            "Mono_Stereo" => array("title" => 'Mono or Stereo', 'field' => "monoStereo", "is_required" => 0, "hidden" => 0),
            "Noice_Reduction" => array("title" => 'Noice Reduction', 'field' => "noiceReductions", "is_required" => 0, "hidden" => 0),
            "Gener_Terms" => array("title" => 'Description', 'field' => "records.genreTerms", "is_required" => 0, "hidden" => 0),
            "Contributor" => array("title" => 'Contributor', 'field' => "records.contributor", "is_required" => 0, "hidden" => 0),
            "Gerneration" => array("title" => 'Gerneration', 'field' => "records.generation", "is_required" => 0, "hidden" => 0),
            "Part" => array("title" => 'Part', 'field' => "records.part", "is_required" => 0, "hidden" => 0),
            "Copyrights" => array("title" => 'Copyrights', 'field' => "records.copyrightRestrictions", "is_required" => 0, "hidden" => 0),
            "Duplicates" => array("title" => 'Duplicates', 'field' => "records.duplicatesDerivatives", "is_required" => 0, "hidden" => 0),
            "Related_Meterial" => array("title" => 'Related Meterial', 'field' => "records.relatedMeterial", "is_required" => 0, "hidden" => 0),
            "Condition_Note" => array("title" => 'Condition Note', 'field' => "records.conditionNote", "is_required" => 0, "hidden" => 0)
        );

        return json_encode($this->defaultOrder);
    }

}
