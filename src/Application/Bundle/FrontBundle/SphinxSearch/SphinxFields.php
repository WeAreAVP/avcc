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
    public function prepareFields(EntityManager $entityManager, $recordId)
    {
        $this->record = $entityManager->getRepository('ApplicationFrontBundle:Records')->findOneBy(array('id' => $recordId));



        $this->indexFields['id'] = $this->record->getId();
        $this->indexFields['s_title'] = ($this->record->getTitle()) ? $this->record->getTitle() : "";
        $this->indexFields['title'] = ($this->record->getTitle()) ? $this->record->getTitle() : "";
        $this->indexFields['s_description'] = ($this->record->getDescription()) ? $this->record->getDescription() : "";
        $this->indexFields['description'] = ($this->record->getDescription()) ? $this->record->getDescription() : "";
        $this->indexFields['s_collection_name'] = ($this->record->getCollectionName()) ? $this->record->getCollectionName() : "";
        $this->indexFields['collection_name'] = ($this->record->getCollectionName()) ? $this->record->getCollectionName() : "";
        $this->indexFields['s_creation_date'] = ($this->record->getCreationDate()) ? $this->record->getCreationDate() : "";
        $this->indexFields['creation_date'] = ($this->record->getCreationDate()) ? $this->record->getCreationDate() : "";
        $this->indexFields['s_content_date'] = ($this->record->getContentDate()) ? $this->record->getContentDate() : "";
        $this->indexFields['content_date'] = ($this->record->getContentDate()) ? $this->record->getContentDate() : "";
        $this->indexFields['unique_id'] = ($this->record->getUniqueId()) ? $this->record->getUniqueId() : "";
        $this->indexFields['s_media_type'] = ($this->record->getMediaType()->getName()) ? $this->record->getMediaType()->getName() : "";
        $this->indexFields['media_type'] = ($this->record->getMediaType()->getName()) ? $this->record->getMediaType()->getName() : "";
        $this->indexFields['s_genre_terms'] = ($this->record->getGenreTerms()) ? $this->record->getGenreTerms() : "";
        $this->indexFields['genre_terms'] = ($this->record->getGenreTerms()) ? $this->record->getGenreTerms() : "";
        $this->indexFields['s_contributor'] = ($this->record->getContributor()) ? $this->record->getContributor() : "";
        $this->indexFields['contributor'] = ($this->record->getContributor()) ? $this->record->getContributor() : "";
        $this->indexFields['location'] = ($this->record->getLocation()) ? $this->record->getLocation() : "";
        $this->indexFields['s_format'] = ($this->record->getFormat()->getName()) ? $this->record->getFormat()->getName() : "";
        $this->indexFields['format'] = ($this->record->getFormat()->getName()) ? $this->record->getFormat()->getName() : "";
        $this->indexFields['is_review'] = ($this->record->getIsReview()) ? $this->record->getIsReview() : "";
        $this->indexFields['commercial'] = ($this->record->getCommercial()) ? $this->record->getCommercial()->getName() : '';
        $this->indexFields['reel_diameter'] = ($this->record->getReelDiameters()) ? $this->record->getReelDiameters()->getName() : "";
//		$this->indexFields['s_reel_diameter'] = ($this->record->getReelDiameters()) ? $this->record->getReelDiameters()->getName() : "";
        $this->indexFields['content_duration'] = ($this->record->getContentDuration()) ? $this->record->getContentDuration() : "";
        $this->indexFields['part'] = ($this->record->getPart()) ? $this->record->getPart() : "";
        $this->indexFields['generation'] = ($this->record->getGeneration()) ? $this->record->getGeneration() : "";

        if ($this->record->getMediaType()->getId() == 1) {
            $this->prepareAudioFields();
        }

        return $this->indexFields;
    }

    /**
     * 
     */
    private function prepareAudioFields()
    {
        $this->indexFields['disk_diameter'] = ($this->record->getAudioRecord()->getDiskDiameters()) ? $this->record->getAudioRecord()->getDiskDiameters()->getName() : "";
        $this->indexFields['base'] = ($this->record->getAudioRecord()->getBases()) ? $this->record->getAudioRecord()->getBases()->getName() : "";
        $this->indexFields['s_base'] = ($this->record->getAudioRecord()->getBases()) ? $this->record->getAudioRecord()->getBases()->getName() : "";
        $this->indexFields['media_diameter'] = ($this->record->getAudioRecord()->getMediaDiameters()) ? $this->record->getAudioRecord()->getMediaDiameters()->getName() : "";
        $this->indexFields['media_duration'] = ($this->record->getAudioRecord()->getMediaDuration()) ? $this->record->getAudioRecord()->getMediaDuration() : "";
        $this->indexFields['recording_speed'] = ($this->record->getAudioRecord()->getRecordingSpeed()) ? $this->record->getAudioRecord()->getRecordingSpeed()->getName() : "";
        $this->indexFields['tape_thickness'] = ($this->record->getAudioRecord()->getTapeThickness()) ? $this->record->getAudioRecord()->getTapeThickness()->getName() : "";
        $this->indexFields['slides'] = ($this->record->getAudioRecord()->getSlides()) ? $this->record->getAudioRecord()->getSlides()->getName() : "";
        $this->indexFields['track_type'] = ($this->record->getAudioRecord()->getTrackTypes()) ? $this->record->getAudioRecord()->getTrackTypes()->getName() : "";
        $this->indexFields['mono_stereo'] = ($this->record->getAudioRecord()->getMonoStereo()) ? $this->record->getAudioRecord()->getMonoStereo()->getName() : "";
        $this->indexFields['noice_reduction'] = ($this->record->getAudioRecord()->getNoiceReduction()) ? $this->record->getAudioRecord()->getNoiceReduction()->getName() : "";
    }

}
