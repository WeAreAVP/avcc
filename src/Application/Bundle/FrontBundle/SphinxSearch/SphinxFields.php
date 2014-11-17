<?php

namespace Application\Bundle\FrontBundle\SphinxSearch;

use Doctrine\ORM\EntityManager;

class SphinxFields
{

    private $indexFields = array();
    private $record = null;

    /**
     * 
     * @param EntityManager $entityManager
     * @param int $recordId
     * @return type
     */
    public function prepareFields(EntityManager $entityManager, $recordId, $recordTypeId)
    {

        if ($recordTypeId == 1) {
            $this->record = $entityManager->getRepository('ApplicationFrontBundle:AudioRecords')->findOneBy(array('id' => $recordId));
            $this->prepareAudioFields();
        } elseif ($recordTypeId == 2) {
            $this->record = $entityManager->getRepository('ApplicationFrontBundle:FilmRecords')->findOneBy(array('id' => $recordId));
            $this->prepareFilmFields();
        } else {
            $this->record = $entityManager->getRepository('ApplicationFrontBundle:VideoRecords')->findOneBy(array('id' => $recordId));
            $this->prepareVideoFields();
        }
//echo '<pre>';print_r($this->record);exit;
        $this->indexFields['id'] = $this->record->getRecord()->getId();
        $this->indexFields['s_title'] = ($this->record->getRecord()->getRecord()->getTitle()) ? $this->record->getRecord()->getTitle() : "";
        $this->indexFields['title'] = ($this->record->getRecord()->getTitle()) ? $this->record->getRecord()->getTitle() : "";
        $this->indexFields['s_description'] = ($this->record->getRecord()->getDescription()) ? $this->record->getRecord()->getDescription() : "";
        $this->indexFields['description'] = ($this->record->getRecord()->getDescription()) ? $this->record->getRecord()->getDescription() : "";
        $this->indexFields['s_collection_name'] = ($this->record->getRecord()->getCollectionName()) ? $this->record->getRecord()->getCollectionName() : "";
        $this->indexFields['collection_name'] = ($this->record->getRecord()->getCollectionName()) ? $this->record->getRecord()->getCollectionName() : "";
        $this->indexFields['s_creation_date'] = ($this->record->getRecord()->getCreationDate()) ? $this->record->getRecord()->getCreationDate() : "";
        $this->indexFields['creation_date'] = ($this->record->getRecord()->getCreationDate()) ? $this->record->getRecord()->getCreationDate() : "";
        $this->indexFields['s_content_date'] = ($this->record->getRecord()->getContentDate()) ? $this->record->getRecord()->getContentDate() : "";
        $this->indexFields['content_date'] = ($this->record->getRecord()->getContentDate()) ? $this->record->getRecord()->getContentDate() : "";
        $this->indexFields['unique_id'] = ($this->record->getRecord()->getUniqueId()) ? $this->record->getRecord()->getUniqueId() : "";
        $this->indexFields['s_media_type'] = ($this->record->getRecord()->getMediaType()->getName()) ? $this->record->getRecord()->getMediaType()->getName() : "";
        $this->indexFields['media_type'] = ($this->record->getRecord()->getMediaType()->getName()) ? $this->record->getRecord()->getMediaType()->getName() : "";
        $this->indexFields['s_genre_terms'] = ($this->record->getRecord()->getGenreTerms()) ? $this->record->getRecord()->getGenreTerms() : "";
        $this->indexFields['genre_terms'] = ($this->record->getRecord()->getGenreTerms()) ? $this->record->getRecord()->getGenreTerms() : "";
        $this->indexFields['s_contributor'] = ($this->record->getRecord()->getContributor()) ? $this->record->getRecord()->getContributor() : "";
        $this->indexFields['contributor'] = ($this->record->getRecord()->getContributor()) ? $this->record->getRecord()->getContributor() : "";
        $this->indexFields['location'] = ($this->record->getRecord()->getLocation()) ? $this->record->getRecord()->getLocation() : "";
        $this->indexFields['s_format'] = ($this->record->getRecord()->getFormat()->getName()) ? $this->record->getRecord()->getFormat()->getName() : "";
        $this->indexFields['format'] = ($this->record->getRecord()->getFormat()->getName()) ? $this->record->getRecord()->getFormat()->getName() : "";
        $this->indexFields['is_review'] = ($this->record->getRecord()->getIsReview()) ? $this->record->getRecord()->getIsReview() : "";
        $this->indexFields['commercial'] = ($this->record->getRecord()->getCommercial()) ? $this->record->getRecord()->getCommercial()->getName() : '';
        $this->indexFields['reel_diameter'] = ($this->record->getRecord()->getReelDiameters()) ? $this->record->getRecord()->getReelDiameters()->getName() : "";
//		$this->indexFields['s_reel_diameter'] = ($this->record->getRecord()->getReelDiameters()) ? $this->record->getRecord()->getReelDiameters()->getName() : "";
        $this->indexFields['content_duration'] = ($this->record->getRecord()->getContentDuration()) ? $this->record->getRecord()->getContentDuration() : "";
        $this->indexFields['part'] = ($this->record->getRecord()->getPart()) ? $this->record->getRecord()->getPart() : "";
        $this->indexFields['generation'] = ($this->record->getRecord()->getGeneration()) ? $this->record->getRecord()->getGeneration() : "";

        
        return $this->indexFields;
    }

    /**
     * Audio fields
     */
    private function prepareAudioFields()
    {
        $this->indexFields['disk_diameter'] = ($this->record->getDiskDiameters()) ? $this->record->getDiskDiameters()->getName() : "";
        $this->indexFields['base'] = ($this->record->getBases()) ? $this->record->getBases()->getName() : "";
        $this->indexFields['s_base'] = ($this->record->getBases()) ? $this->record->getBases()->getName() : "";
        $this->indexFields['media_diameter'] = ($this->record->getMediaDiameters()) ? $this->record->getMediaDiameters()->getName() : "";
        $this->indexFields['media_duration'] = ($this->record->getMediaDuration()) ? $this->record->getMediaDuration() : "";
        $this->indexFields['recording_speed'] = ($this->record->getRecordingSpeed()) ? $this->record->getRecordingSpeed()->getName() : "";
        $this->indexFields['tape_thickness'] = ($this->record->getTapeThickness()) ? $this->record->getTapeThickness()->getName() : "";
        $this->indexFields['slides'] = ($this->record->getSlides()) ? $this->record->getSlides()->getName() : "";
        $this->indexFields['track_type'] = ($this->record->getTrackTypes()) ? $this->record->getTrackTypes()->getName() : "";
        $this->indexFields['mono_stereo'] = ($this->record->getMonoStereo()) ? $this->record->getMonoStereo()->getName() : "";
        $this->indexFields['noice_reduction'] = ($this->record->getNoiceReduction()) ? $this->record->getNoiceReduction()->getName() : "";
    }

    /**
     * Film fields
     */
    private function prepareFilmFields()
    {
        $this->indexFields['s_print_type'] = $this->indexFields['print_type'] = ($this->record->getPrintType()) ? $this->record->getPrintType()->getName() : "";
        $this->indexFields['reel_core'] = ($this->record->getReelCore()) ? $this->record->getReelCore()->getName() : "";
        $this->indexFields['footage'] = ($this->record->getFootage()) ? $this->record->getFootage() : "";
        $this->indexFields['media_diameter'] = ($this->record->getMediaDiameter()) ? $this->record->getMediaDiameter() : "";
        $this->indexFields['base'] = ($this->record->getBases()) ? $this->record->getBases()->getName() : "";
        $this->indexFields['s_base'] = ($this->record->getBases()) ? $this->record->getBases()->getName() : "";
        $this->indexFields['color'] = ($this->record->getColors()) ? $this->record->getColors()->getName() : "";
        $this->indexFields['sound'] = ($this->record->getSound()) ? $this->record->getSound()->getName() : "";
        $this->indexFields['frame_rate'] = ($this->record->getFrameRate()) ? $this->record->getFrameRate()->getName() : "";
        $this->indexFields['acid_detection'] = ($this->record->getAcidDetectionStrip()) ? $this->record->getAcidDetectionStrip()->getName() : "";
        $this->indexFields['shrinkage'] = ($this->record->getShrinkage()) ? $this->record->getShrinkage() : "";
    }

    /**
     * Film fields
     */
    private function prepareVideoFields()
    {
        $this->indexFields['cassette_size'] = ($this->record->getCassetteSize()) ? $this->record->getCassetteSize()->getName() : "";
        $this->indexFields['media_duration'] = ($this->record->getMediaDuration()) ? $this->record->getMediaDuration() : "";
        $this->indexFields['format_version'] = ($this->record->getFormatVersion()) ? $this->record->getFormatVersion()->getName() : "";
        $this->indexFields['recording_speed'] = ($this->record->getRecordingSpeed()) ? $this->record->getRecordingSpeed()->getName() : "";
        $this->indexFields['s_recording_standard'] = $this->indexFields['recording_standard'] = ($this->record->getRecordingStandard()) ? $this->record->getRecordingStandard()->getName() : "";
    }

}
