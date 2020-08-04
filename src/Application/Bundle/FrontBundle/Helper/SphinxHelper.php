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

class SphinxHelper {

    private $keywords;

    public function __construct() {
        $this->keywords = array('title',
            'description', 'collection_name',
            'creation_date', 'content_date', 'genre_terms', 'contributor', 'general_note'
        );
    }

    public function makeSphinxCriteria($facetData) {
        $criteria = null;
        $criteriaArr = null;

        $searchColumns = array(
            'mediaType' => 's_media_type',
            'commercial' => 's_commercial',
            'parent_collection' => 's_parent_collection',
            'format' => 's_format',
            'base' => 's_base',
            'collection_name' => 's_collection_name',
            'recordingStandard' => 's_recording_standard',
            'printType' => 's_print_type',
            'reelDiameter' => 's_reel_diameter',
            'discDiameter' => 's_disk_diameter',
            'acidDetection' => 's_acid_detection',
            'project' => 'project_id',
            'is_review_check' => 'is_review',
            'is_transcription_check' => 'is_transcription',
            'is_digitized_check' => 'is_digitized',
            'has_images_check' => 'has_images',
            'is_reformatting_priority_check' => 'is_reformatting_priority',
            'creationDate' => 's_creation_date',
            'contentDate' => 's_content_date',
            'contentDate' => 's_content_date', 
            'organization_name' => 'organization_id',
            'accessLevel' => 's_access_level'
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
                            $criteriaArr['*'][] = $keyword['value'];
                        }
                    } else {
                        $criteriaArr['s_' . $keyword['type']][] = $keyword['value'];
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
