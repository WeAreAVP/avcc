<?php

namespace Application\Bundle\FrontBundle\SphinxSearch;

use Doctrine\ORM\EntityManager;

class SphinxFields
{

    private $indexFields = array();
    private $record = null;

    /**
     *
     * @param  EntityManager $entityManager
     * @param  int           $recordId
     * @return type
     */
    public function prepareFields(EntityManager $entityManager, $recordId, $recordTypeId)
    {

        $this->record = $entityManager->getRepository('ApplicationFrontBundle:Records')->findOneBy(array('id' => $recordId));

        $this->indexFields['id'] = $this->record->getId();
        $this->indexFields['s_title'] = ($this->record->getTitle()) ? (string) $this->record->getTitle() : "";
        $this->indexFields['title'] = ($this->record->getTitle()) ? (string) $this->record->getTitle() : "";
        $this->indexFields['s_description'] = ($this->record->getDescription()) ? (string) $this->record->getDescription() : "";
        $this->indexFields['description'] = ($this->record->getDescription()) ? (string) $this->record->getDescription() : "";
        $this->indexFields['s_collection_name'] = ($this->record->getCollectionName()) ? (string) $this->record->getCollectionName() : "";
        $this->indexFields['collection_name'] = ($this->record->getCollectionName()) ? (string) $this->record->getCollectionName() : "";
        $this->indexFields['s_creation_date'] = ($this->record->getCreationDate()) ? $this->record->getCreationDate() : "";
        $this->indexFields['creation_date'] = ($this->record->getCreationDate()) ? $this->record->getCreationDate() : "";
        $this->indexFields['s_content_date'] = ($this->record->getContentDate()) ? $this->record->getContentDate() : "";
        $this->indexFields['content_date'] = ($this->record->getContentDate()) ? $this->record->getContentDate() : "";
        $this->indexFields['unique_id'] = ($this->record->getUniqueId()) ? $this->record->getUniqueId() : "";
        $this->indexFields['s_unique_id'] = ($this->record->getUniqueId()) ? $this->record->getUniqueId() : "";
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
        $this->indexFields['s_commercial'] = $this->indexFields['commercial'] = ($this->record->getCommercial()) ? $this->record->getCommercial()->getName() : '';
        $this->indexFields['s_reel_diameter'] = $this->indexFields['reel_diameter'] = ($this->record->getReelDiameters()) ? $this->record->getReelDiameters()->getName() : "";
        $this->indexFields['content_duration'] = ($this->record->getContentDuration()) ? $this->record->getContentDuration() : "";
        $this->indexFields['part'] = ($this->record->getPart()) ? $this->record->getPart() : "";
        $this->indexFields['generation'] = ($this->record->getGeneration()) ? $this->record->getGeneration() : "";
        $this->indexFields['project'] = $this->indexFields['s_project'] = ($this->record->getProject()) ? $this->record->getProject()->getName() : "";
        $this->indexFields['organization_id'] = ($this->record->getProject()->getOrganization()) ? $this->record->getProject()->getOrganization()->getId() : "";
        $this->indexFields['user_id'] = ($this->record->getUser()) ? $this->record->getUser()->getId() : "";
        $this->indexFields['user_name'] = ($this->record->getUser()) ? $this->record->getUser()->getName() : "";
        $this->indexFields['copyright_restrictions'] = ($this->record->getCopyrightRestrictions()) ? $this->record->getCopyrightRestrictions() : "";
        $this->indexFields['duplicates_derivatives'] = ($this->record->getDuplicatesDerivatives()) ? $this->record->getDuplicatesDerivatives() : "";
        $this->indexFields['related_material'] = ($this->record->getRelatedMaterial()) ? $this->record->getRelatedMaterial() : "";
        $this->indexFields['condition_note'] = ($this->record->getConditionNote()) ? $this->record->getConditionNote() : "";
        $this->indexFields['created_on'] = ($this->record->getCreatedOn()) ? $this->record->getCreatedOn()->format('Y-m-d H:i:s') : "";
        $this->indexFields['updated_on'] = ($this->record->getUpdatedOn()) ? $this->record->getUpdatedOn()->format('Y-m-d H:i:s') : "";
        $this->indexFields['project_id'] = ($this->record->getProject()) ? $this->record->getProject()->getId() : "";
        $this->indexFields['width'] = ($this->record->getFormat()) ? (double) $this->record->getFormat()->getWidth() : 0;
        if ($this->record->getAudioRecord()) {
            $this->prepareAudioFields();
        } elseif ($this->record->getFilmRecord()) {
            $this->prepareFilmFields();
        } elseif ($this->record->getVideoRecord()) {
            $this->prepareVideoFields();
        }

        return $this->indexFields;
    }

    /**
     * Audio fields
     */
    private function prepareAudioFields()
    {
        $this->indexFields['s_disk_diameter'] = $this->indexFields['disk_diameter'] = ($this->record->getAudioRecord()->getDiskDiameters()) ? $this->record->getAudioRecord()->getDiskDiameters()->getName() : "";
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

    /**
     * Film fields
     */
    private function prepareFilmFields()
    {
        $this->indexFields['s_print_type'] = $this->indexFields['print_type'] = ($this->record->getFilmRecord()->getPrintType()) ? $this->record->getFilmRecord()->getPrintType()->getName() : "";
        $this->indexFields['reel_core'] = ($this->record->getFilmRecord()->getReelCore()) ? $this->record->getFilmRecord()->getReelCore()->getName() : "";
        $this->indexFields['footage'] = ($this->record->getFilmRecord()->getFootage()) ? $this->record->getFilmRecord()->getFootage() : "";
        $this->indexFields['media_diameter'] = ($this->record->getFilmRecord()->getMediaDiameter()) ? $this->record->getFilmRecord()->getMediaDiameter() : "";
        $this->indexFields['base'] = ($this->record->getFilmRecord()->getBases()) ? $this->record->getFilmRecord()->getBases()->getName() : "";
        $this->indexFields['s_base'] = ($this->record->getFilmRecord()->getBases()) ? $this->record->getFilmRecord()->getBases()->getName() : "";
        $this->indexFields['color'] = ($this->record->getFilmRecord()->getColors()) ? $this->record->getFilmRecord()->getColors()->getName() : "";
        $this->indexFields['sound'] = ($this->record->getFilmRecord()->getSound()) ? $this->record->getFilmRecord()->getSound()->getName() : "";
        $this->indexFields['frame_rate'] = ($this->record->getFilmRecord()->getFrameRate()) ? $this->record->getFilmRecord()->getFrameRate()->getName() : "";
        $this->indexFields['s_acid_detection'] = $this->indexFields['acid_detection'] = ($this->record->getFilmRecord()->getAcidDetectionStrip()) ? $this->record->getFilmRecord()->getAcidDetectionStrip()->getName() : "";
        $this->indexFields['shrinkage'] = ($this->record->getFilmRecord()->getShrinkage()) ? $this->record->getFilmRecord()->getShrinkage() : "";
    }

    /**
     * Film fields
     */
    private function prepareVideoFields()
    {
        $this->indexFields['cassette_size'] = ($this->record->getVideoRecord()->getCassetteSize()) ? $this->record->getVideoRecord()->getCassetteSize()->getName() : "";
        $this->indexFields['media_duration'] = ($this->record->getVideoRecord()->getMediaDuration()) ? $this->record->getVideoRecord()->getMediaDuration() : "";
        $this->indexFields['format_version'] = ($this->record->getVideoRecord()->getFormatVersion()) ? $this->record->getVideoRecord()->getFormatVersion()->getName() : "";
        $this->indexFields['recording_speed'] = ($this->record->getVideoRecord()->getRecordingSpeed()) ? $this->record->getVideoRecord()->getRecordingSpeed()->getName() : "";
        $this->indexFields['s_recording_standard'] = $this->indexFields['recording_standard'] = ($this->record->getVideoRecord()->getRecordingStandard()) ? $this->record->getVideoRecord()->getRecordingStandard()->getName() : "";
    }

}
