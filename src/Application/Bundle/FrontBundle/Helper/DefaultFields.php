<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */

namespace Application\Bundle\FrontBundle\Helper;

use Doctrine\ORM\EntityManager;
use Application\Bundle\FrontBundle\Entity\Users;

class DefaultFields {

    private $defaultOrder = array();
    private $em;

    public function __construct() {
        
    }

    public function getDefaultOrder() {
        $this->defaultOrder['audio'] = array(
            "Media_Type" => array("title" => 'Media Type', 'field' => "record.mediaType", "is_required" => 1, "hidden" => 0),
            "Project_Name" => array("title" => 'Project Name', 'field' => "record.project", "is_required" => 1, "hidden" => 0),
            "Unique_Id" => array("title" => 'Unique Id', 'field' => "record.uniqueId", "is_required" => 1, "hidden" => 0),
            "Alternate_Id" => array("title" => 'Alternate Id', 'field' => "record.alternateId", "is_required" => 0, "hidden" => 0),
            "Location" => array("title" => 'Location', 'field' => "record.location", "is_required" => 1, "hidden" => 0),
            "Format" => array("title" => 'Format', 'field' => "record.format", "is_required" => 1, "hidden" => 0),
            "Title" => array("title" => 'Title', 'field' => "record.title", "is_required" => 1, "hidden" => 0),
            "Collection_Name" => array("title" => 'Collection Name', 'field' => "record.collectionName", "is_required" => 0, "hidden" => 0),
            "Description" => array("title" => 'Description', 'field' => "record.description", "is_required" => 0, "hidden" => 0),
            "Commercial" => array("title" => 'Commercial', 'field' => "record.commercial", "is_required" => 0, "hidden" => 0),
            "Disk_Diameter" => array("title" => 'Disk Diameter', 'field' => "diskDiameters", "is_required" => 0, "hidden" => 0),
            "Reel_Diameter" => array("title" => 'Reel Diameter', 'field' => "record.reelDiameters", "is_required" => 0, "hidden" => 0),
            "Media_Diameter" => array("title" => 'Media Diameter', 'field' => "mediaDiameters", "is_required" => 0, "hidden" => 0),
            "Base" => array("title" => 'Base', 'field' => "bases", "is_required" => 0, "hidden" => 0),
            "Content_Duration" => array("title" => 'Content Duration', 'field' => "record.contentDuration", "is_required" => 0, "hidden" => 0),
            "Media_Duration" => array("title" => 'Media Duration', 'field' => "mediaDuration", "is_required" => 0, "hidden" => 0),
            "Creation_Date" => array("title" => 'Creation Date', 'field' => "record.creationDate", "is_required" => 0, "hidden" => 0),
            "Content_Date" => array("title" => 'Content Date', 'field' => "record.contentDate", "is_required" => 0, "hidden" => 0),
            "Manager_Review" => array("title" => 'Manager Review', 'field' => "record.isReview", "is_required" => 0, "hidden" => 0),
            "General_Notes" => array("title" => 'General Notes', 'field' => "record.generalNote", "is_required" => 0, "hidden" => 0),
            "Reformatting_Priority" => array("title" => 'Reformatting Priority', 'field' => "record.reformattingPriority", "is_required" => 0, "hidden" => 0),
            "Recording_Speed" => array("title" => 'Recording Speed', 'field' => "recordingSpeed", "is_required" => 0, "hidden" => 0),
            "Tape_Thickness" => array("title" => 'Tape Thickness', 'field' => "tapeThickness", "is_required" => 0, "hidden" => 0),
            "Slides" => array("title" => 'Sides', 'field' => "slides", "is_required" => 0, "hidden" => 0),
            "Track_Type" => array("title" => 'Track Type', 'field' => "trackTypes", "is_required" => 0, "hidden" => 0),
            "Mono_Stereo" => array("title" => 'Mono or Stereo', 'field' => "monoStereo", "is_required" => 0, "hidden" => 0),
            "Noice_Reduction" => array("title" => 'Noise Reduction', 'field' => "noiceReduction", "is_required" => 0, "hidden" => 0),
            "Gener_Terms" => array("title" => 'Genre Terms', 'field' => "record.genreTerms", "is_required" => 0, "hidden" => 0),
            "Contributor" => array("title" => 'Contributor', 'field' => "record.contributor", "is_required" => 0, "hidden" => 0),
            "Gerneration" => array("title" => 'Generation', 'field' => "record.generation", "is_required" => 0, "hidden" => 0),
            "Part" => array("title" => 'Part', 'field' => "record.part", "is_required" => 0, "hidden" => 0),
            "Copyright" => array("title" => 'Copyright', 'field' => "record.copyrightRestrictions", "is_required" => 0, "hidden" => 0),
            "Duplicates" => array("title" => 'Duplicates', 'field' => "record.duplicatesDerivatives", "is_required" => 0, "hidden" => 0),
            "Related_Meterial" => array("title" => 'Related Material', 'field' => "record.relatedMaterial", "is_required" => 0, "hidden" => 0),
            "Condition_Note" => array("title" => 'Condition Note', 'field' => "record.conditionNote", "is_required" => 0, "hidden" => 0),
            "Parent_Collection" => array("title" => 'Parent Collection', 'field' => "record.parentCollection", "is_required" => 0, "hidden" => 0),
            "Digitized" => array("title" => 'Digitized', 'field' => "record.digitized", "is_required" => 0, "hidden" => 0),
            "Digitized_By" => array("title" => 'Digitized By', 'field' => "record.digitizedBy", "is_required" => 0, "hidden" => 0),
            "Digitized_When" => array("title" => 'Digitized When', 'field' => "record.digitizedWhen", "is_required" => 0, "hidden" => 0),
            "Urn" => array("title" => 'URN', 'field' => "record.urn", "is_required" => 0, "hidden" => 0),
            "Transcription" => array("title" => 'Transcription', 'field' => "record.transcription", "is_required" => 0, "hidden" => 0),
            "Show_Images" => array("title" => 'Show Images', 'field' => "", "is_required" => 0, "hidden" => 0),
        );

        $this->defaultOrder['video'] = array(
            "Media_Type" => array("title" => 'Media Type', 'field' => "record.mediaType", "is_required" => 1, "hidden" => 0),
            "Project_Name" => array("title" => 'Project Name', 'field' => "record.project", "is_required" => 1, "hidden" => 0),
            "Unique_Id" => array("title" => 'Unique Id', 'field' => "record.uniqueId", "is_required" => 1, "hidden" => 0),
            "Alternate_Id" => array("title" => 'Alternate Id', 'field' => "record.alternateId", "is_required" => 0, "hidden" => 0),
            "Location" => array("title" => 'Location', 'field' => "record.location", "is_required" => 1, "hidden" => 0),
            "Format" => array("title" => 'Format', 'field' => "record.format", "is_required" => 1, "hidden" => 0),
            "Title" => array("title" => 'Title', 'field' => "record.title", "is_required" => 1, "hidden" => 0),
            "Collection_Name" => array("title" => 'Collection Name', 'field' => "record.collectionName", "is_required" => 0, "hidden" => 0),
            "Description" => array("title" => 'Description', 'field' => "record.description", "is_required" => 0, "hidden" => 0),
            "Commercial" => array("title" => 'Commercial', 'field' => "record.commercial", "is_required" => 0, "hidden" => 0),
            "Cassette_Size" => array("title" => 'Cassette Size', 'field' => "cassetteSize", "is_required" => 0, "hidden" => 0),
            "Reel_Diameter" => array("title" => 'Reel Diameter', 'field' => "record.reelDiameters", "is_required" => 0, "hidden" => 0),
            "Content_Duration" => array("title" => 'Content Duration', 'field' => "record.contentDuration", "is_required" => 0, "hidden" => 0),
            "Media_Duration" => array("title" => 'Media Duration', 'field' => "mediaDuration", "is_required" => 0, "hidden" => 0),
            "Creation_Date" => array("title" => 'Creation Date', 'field' => "record.creationDate", "is_required" => 0, "hidden" => 0),
            "Content_Date" => array("title" => 'Content Date', 'field' => "record.contentDate", "is_required" => 0, "hidden" => 0),
            "Manager_Review" => array("title" => 'Manager Review', 'field' => "record.isReview", "is_required" => 0, "hidden" => 0),
            "General_Notes" => array("title" => 'General Notes', 'field' => "record.generalNote", "is_required" => 0, "hidden" => 0),
            "Reformatting_Priority" => array("title" => 'Reformatting Priority', 'field' => "record.reformattingPriority", "is_required" => 0, "hidden" => 0),
            "Format_Version" => array("title" => 'Format Version', 'field' => "formatVersion", "is_required" => 0, "hidden" => 0),
            "Recording_Speed" => array("title" => 'Recording Speed', 'field' => "recordingSpeed", "is_required" => 0, "hidden" => 0),
            "Recording_Standard" => array("title" => 'Recording Standard', 'field' => "recordingStandard", "is_required" => 0, "hidden" => 0),
            "Gener_Terms" => array("title" => 'Genre Terms', 'field' => "record.genreTerms", "is_required" => 0, "hidden" => 0),
            "Contributor" => array("title" => 'Contributor', 'field' => "record.contributor", "is_required" => 0, "hidden" => 0),
            "Gerneration" => array("title" => 'Generation', 'field' => "record.generation", "is_required" => 0, "hidden" => 0),
            "Part" => array("title" => 'Part', 'field' => "record.part", "is_required" => 0, "hidden" => 0),
            "Copyright" => array("title" => 'Copyright', 'field' => "record.copyrightRestrictions", "is_required" => 0, "hidden" => 0),
            "Duplicates" => array("title" => 'Duplicates', 'field' => "record.duplicatesDerivatives", "is_required" => 0, "hidden" => 0),
            "Related_Meterial" => array("title" => 'Related Material', 'field' => "record.relatedMaterial", "is_required" => 0, "hidden" => 0),
            "Condition_Note" => array("title" => 'Condition Note', 'field' => "record.conditionNote", "is_required" => 0, "hidden" => 0),
            "Parent_Collection" => array("title" => 'Parent Collection', 'field' => "record.parentCollection", "is_required" => 0, "hidden" => 0),
            "Digitized" => array("title" => 'Digitized', 'field' => "record.digitized", "is_required" => 0, "hidden" => 0),
            "Digitized_By" => array("title" => 'Digitized By', 'field' => "record.digitizedBy", "is_required" => 0, "hidden" => 0),
            "Digitized_When" => array("title" => 'Digitized When', 'field' => "record.digitizedWhen", "is_required" => 0, "hidden" => 0),
            "Urn" => array("title" => 'URN', 'field' => "record.urn", "is_required" => 0, "hidden" => 0),
            "Transcription" => array("title" => 'Transcription', 'field' => "record.transcription", "is_required" => 0, "hidden" => 0),
            "Show_Images" => array("title" => 'Show Images', 'field' => "", "is_required" => 0, "hidden" => 0),
        );

        $this->defaultOrder['film'] = array(
            "Media_Type" => array("title" => 'Media Type', 'field' => "record.mediaType", "is_required" => 1, "hidden" => 0),
            "Project_Name" => array("title" => 'Project Name', 'field' => "record.project", "is_required" => 1, "hidden" => 0),
            "Unique_Id" => array("title" => 'Unique Id', 'field' => "record.uniqueId", "is_required" => 1, "hidden" => 0),
            "Alternate_Id" => array("title" => 'Alternate Id', 'field' => "record.alternateId", "is_required" => 0, "hidden" => 0),
            "Location" => array("title" => 'Location', 'field' => "record.location", "is_required" => 1, "hidden" => 0),
            "Format" => array("title" => 'Format', 'field' => "record.format", "is_required" => 1, "hidden" => 0),
            "Print_Type" => array("title" => 'Print Type', 'field' => "printType", "is_required" => 1, "hidden" => 0),
            "Title" => array("title" => 'Title', 'field' => "record.title", "is_required" => 1, "hidden" => 0),
            "Collection_Name" => array("title" => 'Collection Name', 'field' => "record.collectionName", "is_required" => 0, "hidden" => 0),
            "Description" => array("title" => 'Description', 'field' => "record.description", "is_required" => 0, "hidden" => 0),
            "Commercial" => array("title" => 'Commercial', 'field' => "record.commercial", "is_required" => 0, "hidden" => 0),
            "Reel_Core" => array("title" => 'Reel or Core', 'field' => "reelCore", "is_required" => 0, "hidden" => 0),
            "Reel_Diameter" => array("title" => 'Reel Diameter', 'field' => "record.reelDiameters", "is_required" => 0, "hidden" => 0),
            "Footage" => array("title" => 'Footage', 'field' => "footage", "is_required" => 0, "hidden" => 0),
            "Media_Diameter" => array("title" => 'Media Diameter (inches)', 'field' => "mediaDiameter", "is_required" => 0, "hidden" => 0),
            "Base" => array("title" => 'Base', 'field' => "bases", "is_required" => 0, "hidden" => 0),
            "Color" => array("title" => 'Color', 'field' => "colors", "is_required" => 0, "hidden" => 0),
            "Sound" => array("title" => 'Sound', 'field' => "sound", "is_required" => 0, "hidden" => 0),
            "Edge_Code/_Year" => array("title" => 'Edge Code/ Year', 'field' => "edgeCodeYear", "is_required" => 0, "hidden" => 0),
            "Content_Duration" => array("title" => 'Content Duration', 'field' => "record.contentDuration", "is_required" => 0, "hidden" => 0),
            "Creation_Date" => array("title" => 'Creation Date', 'field' => "record.creationDate", "is_required" => 0, "hidden" => 0),
            "Content_Date" => array("title" => 'Content Date', 'field' => "record.contentDate", "is_required" => 0, "hidden" => 0),
            "Manager_Review" => array("title" => 'Manager Review', 'field' => "record.isReview", "is_required" => 0, "hidden" => 0),
            "General_Notes" => array("title" => 'General Notes', 'field' => "record.generalNote", "is_required" => 0, "hidden" => 0),
            "Reformatting_Priority" => array("title" => 'Reformatting Priority', 'field' => "record.reformattingPriority", "is_required" => 0, "hidden" => 0),
            "Frame_Rate" => array("title" => 'Frame Rate', 'field' => "frameRate", "is_required" => 0, "hidden" => 0),
            "Acid_Detection_Strip" => array("title" => 'Acid Detection Strip', 'field' => "acidDetectionStrip", "is_required" => 0, "hidden" => 0),
            "Shrinkage" => array("title" => 'Shrinkage', 'field' => "shrinkage", "is_required" => 0, "hidden" => 0),
            "Gener_Terms" => array("title" => 'Genre Terms', 'field' => "record.genreTerms", "is_required" => 0, "hidden" => 0),
            "Contributor" => array("title" => 'Contributor', 'field' => "record.contributor", "is_required" => 0, "hidden" => 0),
            "Gerneration" => array("title" => 'Generation', 'field' => "record.generation", "is_required" => 0, "hidden" => 0),
            "Part" => array("title" => 'Part', 'field' => "record.part", "is_required" => 0, "hidden" => 0),
            "Copyright" => array("title" => 'Copyright', 'field' => "record.copyrightRestrictions", "is_required" => 0, "hidden" => 0),
            "Duplicates" => array("title" => 'Duplicates', 'field' => "record.duplicatesDerivatives", "is_required" => 0, "hidden" => 0),
            "Related_Meterial" => array("title" => 'Related Material', 'field' => "record.relatedMaterial", "is_required" => 0, "hidden" => 0),
            "Condition_Note" => array("title" => 'Condition Note', 'field' => "record.conditionNote", "is_required" => 0, "hidden" => 0),
            "Parent_Collection" => array("title" => 'Parent Collection', 'field' => "record.parentCollection", "is_required" => 0, "hidden" => 0),
            "Digitized" => array("title" => 'Digitized', 'field' => "record.digitized", "is_required" => 0, "hidden" => 0),
            "Digitized_By" => array("title" => 'Digitized By', 'field' => "record.digitizedBy", "is_required" => 0, "hidden" => 0),
            "Digitized_When" => array("title" => 'Digitized When', 'field' => "record.digitizedWhen", "is_required" => 0, "hidden" => 0),
            "Urn" => array("title" => 'URN', 'field' => "record.urn", "is_required" => 0, "hidden" => 0),
            "Transcription" => array("title" => 'Transcription', 'field' => "record.transcription", "is_required" => 0, "hidden" => 0),
            "Show_Images" => array("title" => 'Show Images', 'field' => "", "is_required" => 0, "hidden" => 0),
        );

        return json_encode($this->defaultOrder);
    }

    /**
     *  Get Field settings
     */
    public function getFieldSettings(Users $user, EntityManager $em) {
        $settings = $em->getRepository('ApplicationFrontBundle:UserSettings')->findOneBy(array('user' => $user->getId()));
        if ($settings) {
            $viewSettings = $settings->getViewSetting();
        } else {
            $viewSettings = $this->getDefaultOrder();
        }
        $userViewSettings = json_decode($viewSettings, true);

        return $userViewSettings;
    }

    /**
     * Get record related info
     *
     * @param  integer $projectId
     * @return array
     */
    public function getData($mediaType, EntityManager $em, Users $user, $projectId = null, $recordId = null) {
        $data['mediaTypeId'] = $mediaType;
        $data['projectId'] = $projectId;
        $data['userId'] = $user->getId();
        $mediaTypes = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findBy(array(), array('order' => 'ASC'));

        foreach ($mediaTypes as $media) {
            $data['mediaTypesArr'][$media->getId()] = $media->getName();
        }

        $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();

        foreach ($projects as $project) {
            $data['projectsArr'][$project->getId()] = $project->getName();
        }

        $data['mediaType'] = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $data['mediaTypeId']));

        $data['recordId'] = $recordId;

        return $data;
    }

    public function recordDatatableView($records, $session = null) {
        $tableView = array();

        foreach ($records as $mainIndex => $value) {
            $checked = '';
            if ($session->has("allRecords") && $session->get("allRecords") == 1) {
                $checked = 'checked = "checked"';
            } elseif ($session->has("saveRecords")) {
                if (in_array($value['id'], $session->get("saveRecords"))) {
                    $checked = 'checked = "checked"';
                } else {
                    $checked = '';
                }
            }
            $mediaType = $value['media_type'];
//            if ($mediaType == 'Film' || $mediaType == 'Films') {
//                $url = 'record/film/' . $value['id'];
//            } elseif ($mediaType == 'Video' || $mediaType == 'Videos') {
//                $url = 'record/video/' . $value['id'];
//            } else {
            $url = 'record/' . $value['id'];
//            }
            $tableView[$mainIndex][] = '<input id="row_' . $value['id'] . '"' . $checked . '  type="checkbox" name="record_checkbox" class="checkboxes" onclick="" value="' . $value['id'] . '" />';

            $tableView[$mainIndex][] = ($value['project']) ? '<a href="' . $url . '">' . $value['project'] . '</a>' : $value['project'];
//			$tableView[$mainIndex][] = '<a href="' . $url . '">' . $value['title'] . '</a>';
            $tableView[$mainIndex][] = '<a href="' . $url . '">' . $value['format'] . '</a>';
            $tableView[$mainIndex][] = '<a href="' . $url . '">' . $value['unique_id'] . '</a>';

            $tableView[$mainIndex][] = '<a href="' . $url . '">' . $value['title'] . '</a>';

            $tableView[$mainIndex][] = '<a href="' . $url . '">' . $value['collection_name'] . '</a>';



            $tableView[$mainIndex][] = '<a href="' . $url . '">' . $value['location'] . '</a>';
        }

        return $tableView;
    }

    public function getAllVocabularies(EntityManager $em) {
        $vocabularies = null;
        $vocabularies['bases'] = $em->getRepository('ApplicationFrontBundle:Bases')->getAllAsArray();
        $vocabularies['cassetteSizes'] = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->getAllAsArray();
        $vocabularies['colors'] = $em->getRepository('ApplicationFrontBundle:Colors')->getAllAsArray();
        $vocabularies['commercial'] = $em->getRepository('ApplicationFrontBundle:Commercial')->getAllAsArray();
        $vocabularies['diskDiameters'] = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->getAllAsArray();
        $vocabularies['formatVersions'] = $em->getRepository('ApplicationFrontBundle:FormatVersions')->getAllAsArray();
        $vocabularies['formats'] = $em->getRepository('ApplicationFrontBundle:Formats')->getAllAsArray();
        $vocabularies['parentCollection'] = $em->getRepository('ApplicationFrontBundle:ParentCollection')->getAllAsArray();
        $vocabularies['frameRates'] = $em->getRepository('ApplicationFrontBundle:FrameRates')->getAllAsArray();
        $vocabularies['mediaDiameters'] = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->getAllAsArray();
        $vocabularies['mediaTypes'] = $em->getRepository('ApplicationFrontBundle:MediaTypes')->getAllAsArray();
        $vocabularies['monoStereo'] = $em->getRepository('ApplicationFrontBundle:MonoStereo')->getAllAsArray();
        $vocabularies['noiseReduction'] = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->getAllAsArray();
        $vocabularies['printTypes'] = $em->getRepository('ApplicationFrontBundle:PrintTypes')->getAllAsArray();
        $vocabularies['recordingSpeed'] = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->getAllAsArray();
        $vocabularies['recordingStandards'] = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->getAllAsArray();
        $vocabularies['reelCore'] = $em->getRepository('ApplicationFrontBundle:ReelCore')->getAllAsArray();
        $vocabularies['reelDiameters'] = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->getAllAsArray();
        $vocabularies['sides'] = $em->getRepository('ApplicationFrontBundle:Slides')->getAllAsArray();
        $vocabularies['sounds'] = $em->getRepository('ApplicationFrontBundle:Sounds')->getAllAsArray();
        $vocabularies['tapeThickness'] = $em->getRepository('ApplicationFrontBundle:TapeThickness')->getAllAsArray();
        $vocabularies['trackTypes'] = $em->getRepository('ApplicationFrontBundle:TrackTypes')->getAllAsArray();
        $vocabularies['acidDetectionStrips'] = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->getAllAsArray();

        return $vocabularies;
    }

    public function getToolTip($media) {
        $tooltip = array();
        $tooltip['mediaType'] = 'Automatically entered based on template selection. Used for reporting purposes and to help differentiate formats like VHS and A-DAT that can use the same physical media but be totally different recording types. This field is required.';
        $tooltip['uniqueId'] = 'A free text field for a unique number assigned to each object during the cataloging process. This number may be an existing ID number already associated with the object or it may be created from scratch; the Admin makes the decision on this matter. When data is collected in the Excel spreadsheet, it is possible to sort the data by Unique ID to check that no two objects share the same number. This field is required.';
        $tooltip['project'] = 'A drop down field identifying the Project this record belongs to. In some cases an organization will only have one project. In others they may decide to approach collections one by one with different interns or staff as available and create multiple projects. This field is required.';
        $tooltip['location'] = 'A free text field containing the object’s location. This field is required.';
        $tooltip['format'] = 'A drop-down field that indicates the standard format of an audio, video, or film object. This field is required.';
        $tooltip['title'] = 'A free text field containing the name of the object. If the object is part of a series, has an alternate title, or is a compilation of many titles, the cataloger may choose to account for this information in the Description field. Assigned titles are often a necessity in archival collections due to poor labeling or the prevalence of non-commercial material. If a title cannot be determined from the object’s documentation, (for example, if the object is a completely blank VHS tape), you may choose to assign the title “Unlabeled.” This field is required.';
        $tooltip['collectionName'] = 'A free text field denoting the object’s parent collection or other collection identifier. This field is required.';
        $tooltip['description'] = 'A free text field containing a description of the object’s content, any contextual information pertaining to the content, and/or the provenance of the physical object. Your institution will decide what information should be included, how it should be written or ordered, and how to communicate to the inventory takers.';
        $tooltip['creationDate'] = 'A free text field noting the date the object itself was created (a later derivative may have a different creation date from the date the content was originally recorded).Consistency is best, but can be difficult when dealing with content that is poorly labeled or inexact in labeling. Maintaining a pattern of yyyy/mm/dd or mm/dd/yyyy will make the data less of a pain to work with in the future.';
        $tooltip['contentDate'] = 'A free text field noting the date the object’s content was created or published.Consistency is best, but can be difficult when dealing with content that is poorly labeled or inexact in labeling. Maintaining a pattern of yyyy/mm/dd or mm/dd/yyyy will make the data less of a pain to work with in the future.';
        $tooltip['contentDuration'] = 'A free text field noting the run time of the object’s content. Expressed in minutes. For reporting purposes round up to the nearest whole number. Do not add any other text.';
        $tooltip['mediaDuration'] = 'A free text field to enter the total possible capacity of the object in instances where the actual content duration is unknown. Expressed in minutes as a whole number. Do not add any other text. For example, a Betacam SP BCT 60MLA tape has a media duration of 60 minutes.';
        $tooltip['isReview'] = 'A check box for the cataloger to tick if the record has some questions. The manager of the project can filter on this field to find records that need review.';
        $tooltip['genreTerms'] = 'A free text field that categorizes the general nature of the object’s content. Your institution will compile a list of relevant genres and subjects.';
        $tooltip['contributor'] = 'A free text field denoting any person involved with the creation of the object’s content. Examples include writers, editors, producers, performers, etc. You may choose to include the person’s title along with the name when he or she fills in this field. Your institution will decide how the Contributor field should be formatted; these formatting specifications will be documented and shared with the catalogers.';
        $tooltip['generation'] = 'A free text or drop-down field that defines the relationship between original material and copies. Your institution will compile a list of the relevant generations; if the collection consists mostly of commercial material, this list may be very short.';
        $tooltip['part'] = 'A free text field that notes if the object is a piece of a larger work. For example, if a full-length film is broken up into four reels, the catalogers may complete this field as “Reel 1 of 4.”';
        $tooltip['copyrightRestrictions'] = 'A free text field that explains the terms surrounding an object’s use. You may choose to include notes on viewing restrictions, use guidelines, and rights holders. It may be a complex rights statement or something as simple as stating that this asset is the property of the institution and any further use must be approved. Your institution will decide what information should be included in this field.';
        $tooltip['duplicatesDerivatives'] = 'A free text field that notes if the institution has multiple original copies of an object or if there are derivatives such as Service Copies. You may choose to include information about the location of these duplicates.';
        $tooltip['relatedMaterial'] = 'A free text field for notes on associated objects.';
        $tooltip['conditionNote'] = 'A free text field containing information identifying chemical or physical damage/degradation that may impact playback (mold, broken cassette, hydrolysis, brittleness, shrinkage, tape damage, etc.) The catalogers may also choose to note the date of the inspection in this field.';
        $tooltip['generalNote'] = '';
        $tooltip['parentCollection'] = '';
        $tooltip['reformattingPriority'] = '';
        $tooltip['digitized'] = '';
        $tooltip['digitizedBy'] = '';
        $tooltip['digitizedWhen'] = '';
        $tooltip['urn'] = '';
        $tooltip['transcription'] = '';
        $tooltip['alternateId'] = '';
        $tooltip['commercial'] = 'A drop down field to identify an object as commercial or unique in nature. Unique may also be used to mean rare.';
        if ($media == 1) {
            $tooltip['reelDiameters'] = 'A drop-down field noting the diameter of the audio object’s reel. Combined with Media Diameter, this field may help catalogers estimate Content Duration. Values include 10.5” NAB, 10.5”, 7”, 5”, 4”, 3”.';
            $tooltip['diskDiameters'] = 'A drop-down field noting the size of an audio disk’s diameter. Values include 7”, 10”, 12”, and 16”. 45RPM disks are assumed to be the same size. Does not cover optical discs.';
            $tooltip['mediaDiameters'] = 'A drop-down field noting the percentage of tape filling a reel in comparison to the actual reel size. Combined with Reel Diameter, this field may help catalogers estimate Content Duration.';
            $tooltip['bases'] = 'A drop-down field noting the base type of the audio object. Values include Paper, Acetate, and Polyester for magnetic tape, and Glass, Shellac, Vinyl, and Aluminum for discs.';
            $tooltip['recordingSpeed'] = 'A drop-down field specifying at what speed the object was recorded, for open reel tape and audio discs. Open reel speeds are documented as Inches Per Second (IPS) and include 15/16, 1 ⅞, 3 ¾, 7 ½, 15, 30, and Variable for recordings made at different speeds. Discs are documented at Revolutions Per Minute (RPM) and include 16, 33 ⅓, 45, 78, and Variable for recordings made at different speeds. Other non-standard speeds may be applicable.';
            $tooltip['tapeThickness'] = 'A drop-down field that defines the thickness of a ¼ inch open reel tape. The values range from .5 mil to 2 mil. Thinner tapes are a higher risk for breaking or stretching during playback. This information may be noted on the tape’s box, though the box may not be a reliable identifier of the actual tape inside. Use of a micrometer will identify the exact thickness, but is a time-consuming process.';
            $tooltip['slides'] = 'A drop-down field that notes if an audio object was recorded on one side or both sides. Applicable primarily to audiocassettes and ¼ inch open reel audio.';
            $tooltip['trackTypes'] = 'A drop-down field that identifies how many tracks are on the object. Primarily applicable to open reel tape and some later production formats such as A-DAT, DAT, DA-88, and others. Values include full track, half-track, quarter track, 8-track, 16-track, and 24-track. For multi-track recordings the number of tracks may be variable depending on the number of microphones used. Select the closest standard value.';
            $tooltip['monoStereo'] = 'A drop-down field that identifies if an audio recording is mono or stereo. This will not necessarily impact playback, but may have an impact on digitization, file size, and the specifications of the resulting file.';
            $tooltip['noiceReduction'] = 'A drop-down field that notes any noise filtering devices used during the object’s recording. Primarily used for magnetic tape recording. Values include Dolby A, Dolby B, Dolby C, Dolby S, Dolby SR, and Dolby HX.';
        } else if ($media == 2) {
            $tooltip['reelDiameters'] = 'A drop-down field noting the diameter of a reel, primarily if the film is stored on a projection reel. Combined with Media Diameter, this field may help catalogers estimate Content Duration if a footage ruler is not available, and may also help plan with rehousing and physical storage space needs.';
            $tooltip['printType'] = 'A drop-down field that indicates if a film print is positive, negative, full coat mag track, or unknown. Very simplified from the standard designations of various film types, but sufficient for high level preservation planning. Required field for film records.';
            $tooltip['reelCore'] = 'A drop-down field denoting if the film is stored on a reel or a core. Cores are generally recommended for long term storage, but there may be instances when a projection reel is more practical.';
            $tooltip['footage'] = 'A free text field for documenting the footage length of a film reel. Meters may be used instead, but in both cases the field will function better in the future if only whole numbers are used with no additional text or marks.';
            $tooltip['mediaDiameter'] = 'An open text field noting the diameter of the actual film on the reel in comparison to the reel size if a footage ruler is not available. May help estimate footage or duration, and may also help plan with rehousing and physical storage space needs.';
            $tooltip['bases'] = 'A drop-down field noting the base of the film -- simplified to Nitrate, Acetate, or Polyester. These will be an important prioritization data point.';
            $tooltip['colors'] = 'A drop-down field that indicates if the film is color, black and white, or both. Color film is a higher risk due to fading.';
            $tooltip['sound'] = 'A drop-down field that indicates how the film’s sound was recorded -- silent, mag stripe, variable density optical, variable area optical, and optical in case the cataloger cannot determine between the two types. Mag stripe film is often a higher preservation risk due to the interaction of the magnetic binder and the film base.';
            $tooltip['frameRate'] = 'A drop-down field that indicates the original frame rate of the recording where discernable.';
            $tooltip['acidDetectionStrip'] = 'A drop-down field that indicates the acid detection rating for acetate film if any has been taken. High A/D ratings should be prioritized for cold storage and/or reformatting.';
            $tooltip['shrinkage'] = 'A free text field to note the shrinkage percentage of the film if measurements have been taken. At certain shrinkage level films cannot be projected and may or may not be able to be reformatted.';
        } else {
            $tooltip['reelDiameters'] = '';
            $tooltip['edgeCodeYear'] = '';
            $tooltip['cassetteSize'] = 'A drop-down field to note if a cassette format that comes in two sizes (ex., U-matic, DigiBeta, Betacam, etc.) is large or small.';
            $tooltip['formatVersion'] = 'A drop-down field providing further information about the format version on a given format. Only applicable to certain formats. This may impact the type of deck required to reformat a video or the ability of a vendor to do that work. Values include: High Band, Low Band, Type A, Type B, Type C.';
            $tooltip['recordingSpeed'] = 'A drop-down field specifying at what speed the object was recorded. Only applies to specific formats that have variable recording speeds, such as VHS. Values include SP, LP, EP, SLP.';
            $tooltip['recordingStandard'] = 'A drop-down field that notes if a tape was created using NTSC, PAL, or SECAM recording standards.';
        }

        return $tooltip;
    }

    public function paidOrganizations($id) {
        $now = date("Y-m-d");
        if ((int) $id == 239) {
            return false;
        } else if ((int) $id == 140) {
            $march = strtotime("1st April 2017");
            if ($now < date("Y-m-d", $march)) {
                return false;
            }
        } else if ((int) $id == 308) {
            $may = strtotime("1st May 2017");
            if ($now < date("Y-m-d", $may)) {
                return false;
            }
        }
        return true;
    }

    public function fields_cmp($default, $db_view) {
        $excluded = $this->excludeExtraFields($db_view, $default);
        $included = $this->includeExtraFields($default, $excluded);
        return json_encode($included);
    }

    function includeExtraFields($default, $db) {
        $new_array = $db;
        foreach ($default as $key => $field) {
            foreach ($field as $key1 => $val) {
                $duplicate = false;
                foreach ($db[$key] as $data2) {
                    if (isset($val['title']) && isset($data2['title']) && $val['title'] === $data2['title']) {
                        $duplicate = true;
                        break;
                    }
                }
                if ($duplicate === false) {
                    $new_array[$key][] = $val;
                }
            }
        }
        return $new_array;
    }

    function excludeExtraFields($db, $default) {
        $new_array = $db;
        foreach ($db as $key => $field) {
            foreach ($field as $key1 => $val) {
                $duplicate = false;
                foreach ($default[$key] as $data2) {
                    if (isset($val['title']) && isset($data2['title']) && $val['title'] === $data2['title']) {
                        $duplicate = true;
                        break;
                    }
                }
                if ($duplicate === false) {
                    unset($new_array[$key][$key1]);
                }
            }
        }
        return $new_array;
    }

}
