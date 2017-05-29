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

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Application\Bundle\FrontBundle\Helper\SphinxHelper;

/**
 * Bulk Edit controller.
 *
 * @Route("/bulkedit")
 */
class BulkEditController extends Controller {

    /**
     * Make records to display for dataTables.
     *
     * @param Request $request
     *
     * @Route("/validation", name="bulkedit_validation")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return json
     */
    public function validation(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $posted = $request->request->all();
            $recordIds = $posted['records'];
            $html = '';
            $errorMsg = '';
            $em = $this->getDoctrine()->getManager();
            $disable = array('mediaType' => 0, "format" => 0);
            if ($recordIds) {
                if ($recordIds == 'all') {
                    $sphinxInfo = $this->getSphinxInfo();
                    $shpinxRecordIds = $this->fetchFromSphinx($this->getUser(), $sphinxInfo, $em);
                    $recordIdsArray = array();
                    foreach ($shpinxRecordIds as $recIds) {
                        $recordIdsArray = $recIds;
                    }
                } else {
                    $recordIdsArray = explode(',', $recordIds);
                }
                $records = $em->getRepository('ApplicationFrontBundle:Records')->findRecordsByIds($recordIdsArray);
                $mediaTypeId = $records[0]->getMediaType()->getId();
                $formatId = $records[0]->getFormat()->getId();
                foreach ($records as $record) {
                    if ($mediaTypeId != $record->getMediaType()->getId()) {
                        $disable["mediaType"] = 1;
                        $disable["format"] = 1;
                    }
                }
                $relatedFields = $this->getRelatedFields();
                $templateParameters = array('selectedrecords' => $recordIds, 'disableFields' => $disable, 'mediaTypeId' => $mediaTypeId, 'relatedFields' => $relatedFields);
                $html = $this->container->get('templating')->render('ApplicationFrontBundle:BulkEdit:bulkedit.html.php', $templateParameters);
                $success = true;
            } else {
                $success = false;
                $errorMsg = 'Select records to edit.';
            }

            echo json_encode(array('success' => $success, 'msg' => $errorMsg, 'html' => $html));
            $session = $this->getRequest()->getSession();
            $session->remove("saveRecords");
            $session->remove("allRecords");
            exit;
        }
    }

    /**
     * Get sphinx parameters
     *
     * @return array
     */
    protected function getSphinxInfo() {
        return $this->container->getParameter('sphinx_param');
    }

    /**
     * Get related field data for bulk edit
     *
     * @return array
     */
    protected function getRelatedFields() {
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $data['mediaTypes'] = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();
        $data['parentCollection'] = $em->getRepository('ApplicationFrontBundle:ParentCollection')->findAll();
        $data['formats'] = $em->getRepository('ApplicationFrontBundle:Formats')->findAll();
        $data['projects'] = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        $data['commercial'] = $em->getRepository('ApplicationFrontBundle:Commercial')->findAll();
        $data['acidDetectionStrips'] = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->findAll();
        $data['colors'] = $em->getRepository('ApplicationFrontBundle:Colors')->findAll();
        $data['sounds'] = $em->getRepository('ApplicationFrontBundle:Sounds')->findAll();
        $data['sides'] = $em->getRepository('ApplicationFrontBundle:Slides')->findAll();
        $data['cassetteSizes'] = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->findAll();
        $data['frameRates'] = $em->getRepository('ApplicationFrontBundle:FrameRates')->findAll();
        $data['monoStereo'] = $em->getRepository('ApplicationFrontBundle:MonoStereo')->findAll();
        $data['noiseReduction'] = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->findAll();
        $data['printTypes'] = $em->getRepository('ApplicationFrontBundle:PrintTypes')->findAll();
        $data['recordingStandards'] = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->findAll();
        $data['reelCore'] = $em->getRepository('ApplicationFrontBundle:ReelCore')->findAll();
        $data['tapeThickness'] = $em->getRepository('ApplicationFrontBundle:TapeThickness')->findAll();
        $data['trackTypes'] = $em->getRepository('ApplicationFrontBundle:TrackTypes')->findAll();
        $data['diskDiameters'] = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->findAll();
        $data['reelDiameters'] = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->findAll();
        $data['mediaDiameters'] = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->findAll();
        $data['bases'] = $em->getRepository('ApplicationFrontBundle:Bases')->findAll();
        $data['recordingSpeed'] = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->findAll();
        $data['formatVersions'] = $em->getRepository('ApplicationFrontBundle:FormatVersions')->findAll();
        $data['tapeThickness'] = $em->getRepository('ApplicationFrontBundle:TapeThickness')->findAll();

        return $data;
    }

    /**
     * Bulk Edit.
     *
     * @param Request $request
     *
     * @Route("/edit", name="bulkedit_edit")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return array
     */
    public function bulkEditAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $posted = $request->request->all();
            $session = $this->getRequest()->getSession();
            $recordIds = $posted['records'];
            $mediaDisable = $posted['mediaDisable'];
            $mediaTypeId = $posted['mediaTypeId'];
            $em = $this->getDoctrine()->getManager();
            $update = false;
            if ($recordIds) {
                if ($recordIds == 'all') {
                    $sphinxInfo = $this->getSphinxInfo();
                    $recordIdsArray = $this->fetchFromSphinx($this->getUser(), $sphinxInfo, $em);
                } else {
                    $recordIdsArray = explode(',', $recordIds);
                }
                foreach ($recordIdsArray as $recordId) {
                    $record = $em->getRepository('ApplicationFrontBundle:Records')->find($recordId);
                    if (isset($posted['format']) && $posted['format']) {
                        $format = $em->getRepository('ApplicationFrontBundle:Formats')->findOneBy(array('id' => $posted['format']));
                        $record->setFormat($format);
                        $update = true;
                    }
                    if ($posted['parentCollection']) {
                        $parentCollection = $em->getRepository('ApplicationFrontBundle:ParentCollection')->findOneBy(array('id' => $posted['parentCollection']));
                        $record->setParentCollection($parentCollection);
                        $update = true;
                    }
                    if ($posted['project']) {
                        $project = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $posted['project']));
                        $record->setProject($project);
                        $update = true;
                    }
                    if ($posted['transcription'] && $posted['transcription'] != 3) {
                        $transcription = ($posted['transcription'] == 1) ? 1 : 0;
                        $record->setTranscription($transcription);
                        $update = true;
                    }
                    if ($posted['digitized'] && $posted['digitized'] != 3) {
                        $digitized = ($posted['digitized'] == 1) ? 1 : 0;
                        $record->setDigitized($digitized);
                        $update = true;
                    }
                    if ($posted['digitizedBy']) {
                        $record->setDigitizedBy($posted['digitizedBy']);
                        $update = true;
                    }
                    if ($posted['digitizedWhen']) {
                        $record->setDigitizedWhen($posted['digitizedWhen']);
                        $update = true;
                    }
                    if ($posted['urn']) {
                        $record->setUrn($posted['urn']);
                        $update = true;
                    }
                    if ($posted['location']) {
                        $record->setLocation($posted['location']);
                        $update = true;
                    }
                    if ($posted['title']) {
                        $record->setTitle($posted['title']);
                        $update = true;
                    }
                    if ($posted['collectionName']) {
                        $record->setCollectionName($posted['collectionName']);
                        $update = true;
                    }
                    if ($posted['description']) {
                        $record->setDescription($posted['description']);
                        $update = true;
                    }
                    if ($posted['copyrightRestrictions']) {
                        $record->setCopyrightRestrictions($posted['copyrightRestrictions']);
                        $update = true;
                    }
                    if ($posted['contentDuration']) {
                        $record->setContentDuration($posted['contentDuration']);
                        $update = true;
                    }
                    if ($posted['commercial']) {
                        $commercial = $em->getRepository('ApplicationFrontBundle:Commercial')->findOneBy(array('id' => $posted['commercial']));
                        $record->setCommercial($commercial);
                        $update = true;
                    }
                    if (!$mediaDisable) {
                        if ($mediaTypeId == 1) {
                            $audioRecord = $em->getRepository('ApplicationFrontBundle:AudioRecords')->findOneBy(array('record' => $record->getId()));
                            if ($posted['reelDiameters']) {
                                $reelD = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->findOneBy(array('id' => $posted['reelDiameters']));
                                $record->setReelDiameters($reelD);
                                $update = true;
                            }
                            $this->updateAudioFields($audioRecord, $posted);
                        } elseif ($mediaTypeId == 2) {
                            $filmRecord = $em->getRepository('ApplicationFrontBundle:FilmRecords')->findOneBy(array('record' => $record->getId()));
                            if ($posted['reelDiameters']) {
                                $reelD = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->findOneBy(array('id' => $posted['reelDiameters']));
                                $record->setReelDiameters($reelD);
                                $update = true;
                            }
                            $this->updateFilmFields($filmRecord, $posted);
                        } elseif ($mediaTypeId == 3) {
                            $videoRecord = $em->getRepository('ApplicationFrontBundle:VideoRecords')->findOneBy(array('record' => $record->getId()));
                            if ($posted['reelDiameters']) {
                                $reelD = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->findOneBy(array('id' => $posted['reelDiameters']));
                                $record->setReelDiameters($reelD);
                                $update = true;
                            }
                            $this->updateVideoFields($videoRecord, $posted);
                        }
                    }
                    if ($update) {
                        $em->flush();
                        $shpinxInfo = $this->getSphinxInfo();
                        $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $record->getId(), $record->getMediaType()->getId());
                        $sphinxSearch->replace();
                    }
                }
            }
            $session->remove("saveRecords");
            $session->remove("allRecords");
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => true));
        }
        exit;
    }

    protected function updateAudioFields($audioRecord, $posted) {
        $em = $this->getDoctrine()->getManager();
        $update = false;
        if ($posted['diskDiameters']) {
            $disk = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->findOneBy(array('id' => $posted['diskDiameters']));
            $audioRecord->setDiskDiameters($disk);
            $update = true;
        }
        if ($posted['mediaDiameters']) {
            $mediaD = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->findOneBy(array('id' => $posted['mediaDiameters']));
            $audioRecord->setMediaDiameters($mediaD);
            $update = true;
        }
        if ($posted['bases']) {
            $base = $em->getRepository('ApplicationFrontBundle:Bases')->findOneBy(array('id' => $posted['bases']));
            $audioRecord->setBases($base);
            $update = true;
        }
        if ($posted['mediaDuration']) {
            $audioRecord->setMediaDuration($posted['mediaDuration']);
            $update = true;
        }
        if ($posted['recordingSpeed']) {
            $recordingSp = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->findOneBy(array('id' => $posted['recordingSpeed']));
            $audioRecord->setRecordingSpeed($recordingSp);
            $update = true;
        }
        if ($posted['tapeThickness']) {
            $tape = $em->getRepository('ApplicationFrontBundle:TapeThickness')->findOneBy(array('id' => $posted['tapeThickness']));
            $audioRecord->setTapeThickness($tape);
            $update = true;
        }
        if ($posted['slides']) {
            $side = $em->getRepository('ApplicationFrontBundle:Slides')->findOneBy(array('id' => $posted['slides']));
            $audioRecord->setSlides($side);
            $update = true;
        }
        if ($posted['trackTypes']) {
            $track = $em->getRepository('ApplicationFrontBundle:TrackTypes')->findOneBy(array('id' => $posted['trackTypes']));
            $audioRecord->setTrackTypes($track);
            $update = true;
        }
        if ($posted['monoStereo']) {
            $monostereo = $em->getRepository('ApplicationFrontBundle:MonoStereo')->findOneBy(array('id' => $posted['monoStereo']));
            $audioRecord->setMonoStereo($monostereo);
            $update = true;
        }
        if ($posted['noiceReduction']) {
            $noise = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->findOneBy(array('id' => $posted['noiceReduction']));
            $audioRecord->setNoiceReduction($noise);
            $update = true;
        }
        if ($update) {
            $em->flush();
        }

        return $update;
    }

    protected function updateVideoFields($videoRecord, $posted) {
        $em = $this->getDoctrine()->getManager();
        $update = false;
        if ($posted['cassetteSize']) {
            $cassete = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->findOneBy(array('id' => $posted['cassetteSize']));
            $videoRecord->setCassetteSize($cassete);
            $update = true;
        }
        if ($posted['formatVersion']) {
            $formatversion = $em->getRepository('ApplicationFrontBundle:FormatVersions')->findOneBy(array('id' => $posted['formatVersion']));
            $videoRecord->setFormatVersion($formatversion);
            $update = true;
        }
        if ($posted['mediaDuration']) {
            $videoRecord->setMediaDuration($posted['mediaDuration']);
            $update = true;
        }
        if ($posted['recordingSpeed']) {
            $recordingSp = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->findOneBy(array('id' => $posted['recordingSpeed']));
            $videoRecord->setRecordingSpeed($recordingSp);
            $update = true;
        }
        if ($posted['recordingStandard']) {
            $recordingSt = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->findOneBy(array('id' => $posted['recordingStandard']));
            $videoRecord->setRecordingStandard($recordingSt);
            $update = true;
        }
        if ($update) {
            $em->flush();
        }

        return $update;
    }

    protected function updateFilmFields($filmRecord, $posted) {
        $em = $this->getDoctrine()->getManager();
        $update = false;
        if ($posted['reelCore']) {
            $reelcore = $em->getRepository('ApplicationFrontBundle:ReelCore')->findOneBy(array('id' => $posted['reelCore']));
            $filmRecord->setReelCore($reelcore);
            $update = true;
        }
        if ($posted['footage']) {
            $filmRecord->setFootage($posted['footage']);
            $update = true;
        }
        if ($posted['mediaDiameter']) {
            $filmRecord->setMediaDiameter($posted['mediaDiameter']);
            $update = true;
        }
        if ($posted['bases']) {
            $base = $em->getRepository('ApplicationFrontBundle:Bases')->findOneBy(array('id' => $posted['bases']));
            $filmRecord->setBases($base);
            $update = true;
        }
        if ($posted['colors']) {
            $color = $em->getRepository('ApplicationFrontBundle:Colors')->findOneBy(array('id' => $posted['colors']));
            $filmRecord->setColors($color);
            $update = true;
        }
        if ($posted['sound']) {
            $sound = $em->getRepository('ApplicationFrontBundle:Sounds')->findOneBy(array('id' => $posted['sound']));
            $filmRecord->setSound($sound);
            $update = true;
        }
        if ($posted['frameRate']) {
            $frame = $em->getRepository('ApplicationFrontBundle:FrameRates')->findOneBy(array('id' => $posted['frameRate']));
            $filmRecord->setFrameRate($frame);
            $update = true;
        }
        if ($posted['acidDetectionStrip']) {
            $strip = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->findOneBy(array('id' => $posted['acidDetectionStrip']));
            $filmRecord->setAcidDetectionStrip($strip);
            $update = true;
        }
        if ($posted['printType']) {
            $print = $em->getRepository('ApplicationFrontBundle:PrintTypes')->findOneBy(array('id' => $posted['printType']));
            $filmRecord->setPrintType($print);
            $update = true;
        }
        if ($update) {
            $em->flush();
        }

        return $update;
    }

    /**
     * Get records from sphinx
     *
     * @param type  $user
     * @param type  $sphinxInfo
     * @param type  $em
     * @param array $columnNames
     *
     * @return array
     */
    protected function fetchFromSphinx($user, $sphinxInfo, $em) {
        $count = 0;
        $offset = 0;
        $recordIds = array();
        $sphinxObj = new SphinxSearch($em, $sphinxInfo);
        $searchOn = $this->criteria();
        $criteria = $searchOn['criteriaArr'];
        while ($count == 0) {
            $records = $sphinxObj->select($user, $offset, 5000, 'id', 'asc', $criteria);
            foreach ($records[0] as $record) {
                $recordIds[] = $record['id'];
            }
            $totalFound = $records[1][1]['Value'];
            $offset = $offset + 5000;
            if ($totalFound < 5000) {
                $count++;
            }
        }
        return $recordIds;
    }

    protected function criteria() {
        $session = $this->getRequest()->getSession();
        $facetData = $session->get('facetData');
        $makeCriteria = new SphinxHelper();
        $criteria = $makeCriteria->makeSphinxCriteria($facetData);

        return $criteria;
    }

}
