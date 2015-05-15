<?php

namespace Application\Bundle\FrontBundle\Helper;

class SphinxHelper {

    private $keywords;

    public function __construct() {
        $this->keywords = array('title',
            'description', 'collection_name',
            'creation_date', 'content_date', 'genre_terms', 'contributor',
        );
    }

    public function makeSphinxCriteria($facetData) {
        $criteria = null;
        $criteriaArr = null;

        $searchColumns = array(
            'mediaType' => 's_media_type',
            'commercial' => 's_commercial',
            'format' => 's_format',
            'base' => 's_base',
            'collectionName' => 's_collection_name',
            'recordingStandard' => 's_recording_standard',
            'printType' => 's_print_type',
            'reelDiameter' => 's_reel_diameter',
            'discDiameter' => 's_disk_diameter',
            'acidDetection' => 's_acid_detection',
            'project' => 's_project',
            'is_review_check' => 'is_review',
            'creationDate' => 's_creation_date',
            'contentDate' => 's_content_date',
            'contentDate' => 's_content_date',
        );

        foreach ($searchColumns as $key => $value) {
            if (isset($facetData[$key])) {
                $criteriaArr[$value] = $facetData[$key];
            }
        }

        if (isset($facetData['facet_keyword_search'])) {
            $keywords = json_decode($facetData['facet_keyword_search'], true);
            if (count($keywords) > 0) {
                foreach ($keywords as $keyword) {
                    if ($keyword['type'] == 'all') {
                        foreach ($this->keywords as $key) {
                            $criteriaArr['*'] = $keyword['value'];
                        }
                    } else {
                        $criteriaArr['s_' . $keyword['type']] = $keyword['value'];
                    }
                }
            }
        }
        if (isset($facetData['parent_facet'])) {
            $criteria['parent_facet'] = $facetData['parent_facet'];
        }

        if ($criteriaArr) {
            $criteria['criteriaArr'] = $criteriaArr;
        }

        return $criteria;
    }

}
