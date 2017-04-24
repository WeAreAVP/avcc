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
use Symfony\Component\HttpFoundation\Session\Session;
use Application\Bundle\FrontBundle\Entity\ImportExport;
use JMS\JobQueueBundle\Entity\Job;
use DateInterval;
use DateTime;
use Application\Bundle\FrontBundle\Components\ImportReport;

/**
 * Import controller.
 *
 * @Route("/import")
 *
 */
class ImportController extends Controller {

    /**
     * Import file
     *
     * @Route("/", name="import_index")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return array
     */
    public function indexAction(Request $request) {
        
    }

    /**
     * Import file
     *
     * @Route("/import", name="import_record")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return array
     */
    public function importAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $fileName = 'allFormat_1417781547.csv';
        $import = new ImportReport($this->container);
        $validation = $import->validateVocabulary($fileName);
        if ($validation) {
            echo '<pre>';
            foreach ($validation as $key => $value) {
                echo '<p><b>' . str_replace('_', ' ', ucfirst($key)) . '</b><br />';
                echo implode('<br />', $value);
                echo '</p>';
            }
        } else {
            $numberOfRecords = $import->getRecordsFromFile($fileName, $this->getUser());
            echo '<pre>';
            print_r($numberOfRecords);
        }

        exit;
    }

    /**
     * Import file
     *
     * @param Request $request
     *
     * @Route("/importRecords", name="import_records")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return array
     */
    public function importRecordsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $type = $data['impfiletype'];
        if ($request->files->get('importfile')) {
            $originalFileName = $request->files->get('importfile')->getClientOriginalName();
            $uploadedFileSize = $request->files->get('importfile')->getClientSize();
            $newFileName = null;
            if ($uploadedFileSize > 0) {
                $folderPath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/';
                if (!is_dir($folderPath))
                    mkdir($folderPath, 0777, TRUE);
                $extension = $request->files->get('importfile')->getClientOriginalExtension();
                $newFileName = $this->getUser()->getId() . "_import" . time() . "." . $extension;
                if ($type == strtolower($extension)) {
                    $request->files->get('importfile')->move($folderPath, $newFileName);
                    if (!$request->files->get('importfile')->isValid()) {
                        echo 'file uploaded';
                    }
                    if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
                        $organizationId = $data['organization'];
                    } else {
                        $organizationId = $this->getUser()->getOrganizations()->getId();
                    }
                    $import = new ImportExport();
                    $import->setUser($this->getUser());
                    $import->setOrganizationId($organizationId);
                    $import->setFormat($type);
                    $import->setType("import");
                    $import->setExistingRecords($data['existingRecords']);
                    $import->setInsertOption($data['submit']);
                    $import->setQueryOrId(0);
                    $import->setFileName($newFileName);
                    $import->setStatus(0);
                    $em->persist($import);
                    $em->flush();

                    $job = new Job('avcc:import-report', array('id' => $import->getId()));
                    $date = new DateTime();
                    $date->add(new DateInterval('PT1M'));
                    $job->setExecuteAfter($date);
                    $em->persist($job);
                    $em->flush($job);
                    $message = array('heading' => 'Import', 'message' => 'Import request successfully sent. You will receive a confirmation email shortly.');
                    $this->get('session')->getFlashBag()->add('report_success', $message);
                } else {
                    $message = array('heading' => 'Import', 'message' => 'File formate is not correct. Please try again.');
                    $this->get('session')->getFlashBag()->add('report_error', $message);
                }
            } else {
                $message = array('heading' => 'Import', 'message' => 'File is empty. Please try again.');
                $this->get('session')->getFlashBag()->add('report_error', $message);
            }
        } else {
            $message = array('heading' => 'Import', 'message' => 'Select file that require to import. Please try again.');
            $this->get('session')->getFlashBag()->add('report_error', $message);
        }

        return $this->redirect($this->generateUrl('record_list'));
    }

    /**
     * Import file
     *
     * @param Request $request
     *
     * @Route("/validateRecords", name="validate_records")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return array
     */
    public function validateImport(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $type = $data['impfiletype'];
        if ($request->files->get('importfile')) {
            $originalFileName = $request->files->get('importfile')->getClientOriginalName();
            $uploadedFileSize = $request->files->get('importfile')->getClientSize();
            $newFileName = null;
            if ($uploadedFileSize > 0) {
                $folderPath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/';
                if (!is_dir($folderPath))
                    mkdir($folderPath, 0777, TRUE);
                $extension = $request->files->get('importfile')->getClientOriginalExtension();
                $newFileName = $this->getUser()->getId() . "_import" . time() . "." . $extension;
                if ($type == strtolower($extension)) {
                    $request->files->get('importfile')->move($folderPath, $newFileName);
//                    if (!$request->files->get('importfile')->isValid()) {
//                        echo 'file uploaded';
//                    }
                    if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
                        $organizationId = $data['organization'];
                    } else {
                        $organizationId = $this->getUser()->getOrganizations()->getId();
                    }

                    $import = new ImportReport($this->container);
                    $validateFields = $import->validateUniqueIdVocab($newFileName, $organizationId);
                    if ($validateFields) {
                        $message = array('success' => true, 'message' => implode("<br>", $validateFields), 'count' => count($validateFields));
                        echo json_encode($message);
                    } else {
                        $message = array('success' => true, 'message' => 'submit');
                        echo json_encode($message);
                    }
                } else {
                    $message = array('success' => false, 'message' => 'File formate is not correct. Please try again.');
                    echo json_encode($message);
                }
            } else {
                $message = array('success' => false, 'message' => 'File is empty. Please try again.');
                echo json_encode($message);
            }
        } else {
            $message = array('success' => false, 'message' => 'Select file that require to import. Please try again.');
            echo json_encode($message);
        }
        exit();
    }

}
