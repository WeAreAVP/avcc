<?php

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
class BulkEditController extends Controller
{

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
    public function validation(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $posted = $request->request->all();
            $recordIds = $posted['records'];
            $html = '';
            $errorMsg = '';
            $em = $this->getDoctrine()->getManager();
            $disable = array('mediaType' => 0);
            if ($recordIds) {
                if ($recordIds == 'all') {
                    $sphinxInfo = $this->getSphinxInfo();
                    $html = "all records";
                } else {
                    $recordIdsArray = explode(',', $recordIds);
                    $records = $em->getRepository('ApplicationFrontBundle:Records')->findRecordsByIds($recordIdsArray);
                    $mediaTypeId = $records[0]->getMediaType()->getId();
                    $formatId = $records[0]->getFormat()->getId();
                    foreach ($records as $record) {
                        if ($mediaTypeId != $record->getMediaType()->getId()) {
                            $disable["mediaType"] = 1;
                            $disable["format"] = 1;
                        }
//                        if($formatId != $record->getFormat()->getId()){
//                            $disable["format"] = 1;
//                        }
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
    protected function getSphinxInfo()
    {
        return $this->container->getParameter('sphinx_param');
    }

    /**
     * Get related field data for bulk edit
     * 
     * @return array
     */
    protected function getRelatedFields()
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $data['mediaTypes'] = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();
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
        return $data;
    }

    /**
     * Bulk Edit.
     *
     * @param Request $request
     *
     * @Route("/edit", name="bulkedit_edit")
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function bulkEditAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $posted = $request->request->all();
            print_r($posted) exit;
            $recordIds = $posted['records'];
            $mediaDisable = $posted['mediaDisable'];
            $em = $this->getDoctrine()->getManager();
            $update = false;
            if ($recordIds) {
                $recordIdsArray = explode(',', $recordIds);
                foreach ($recordIdsArray as $recordId) {
                    $record = $em->getRepository('ApplicationFrontBundle:Records')->find($recordId);
                    if ($posted['project']) {
                        $project = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('name' => $posted['project']));
                        $record->setProject($project);
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
                    if ($posted['contentDuration']) {
                        $record->setContentDuration($posted['contentDuration']);
                        $update = true;
                    }
                    if ($posted['commercial']) {
                        $commercial = $em->getRepository('ApplicationFrontBundle:Commercial')->findOneBy(array('name' => $posted['commercial']));
                        $record->setCommercial($commercial);
                        $update = true;
                    }

                    if ($update) {
//                    $em->persist($record);
                        $em->flush();
                        $shpinxInfo = $this->getSphinxInfo();
                        $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $record->getId(), $record->getMediaType()->getId());
                        $sphinxSearch->replace();
                    }
                }
            }
            return json_encode(array('success' => true));
        } else {
            return json_encode(array('success' => true));
        }
    }

}
