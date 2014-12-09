<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Helper\DefaultFields;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Application\Bundle\FrontBundle\Entity\ImportExport;
use Application\Bundle\FrontBundle\Helper\SphinxHelper;
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
class ImportController extends Controller
{

    /**
     * Import file 
     *
     * @Route("/", name="import_index")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return array
     */
    public function indexAction(Request $request)
    {
//        $em = $this->getDoctrine()->getManager();
//        $data = $request->request->all();
//        $type = $data['impfiletype'];
//        if ($request->files->get('impfile')) {
//            $originalFileName = $request->files->get('importfile')->getClientOriginalName();
//            $uploadedFileSize = $request->files->get('importfile')->getClientSize();
//            $newFileName = null;
//            if ($uploadedFileSize > 0) {
//                $folderPath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/';
//                if (!is_dir($folderPath))
//                    mkdir($folderPath, 0777, TRUE);
//                $extension = $request->files->get('importfile')->getClientOriginalExtension();
//                $newFileName = $this->getUser()->getId() . "_import" . time() . "." . $extension;
//                if ($type == $extension) {
//                    $request->files->get('importfile')->move($folderPath, $newFileName);
//                    if (!$request->files->get('importfile')->isValid()) {
//                        echo 'file uploaded';
//                    }
//                    $import = new ImportExport();
//                    $import->setUser($this->getUser());
//                    $import->setFormat($type);
//                    $import->setType("import");
//                    $import->setFileName($newFileName);
//                    $import->setStatus(0);
//                    $em->persist($import);
//                    $em->flush();
////
////                    $job = new Job('avcc:import-records', array('id' => $import->getId()));
////                    $date = new DateTime();
////                    $date->add(new DateInterval('PT1M'));
////                    $job->setExecuteAfter($date);
////                    $em->persist($job);
////                    $em->flush($job);
//
//                    $this->get('session')->getFlashBag()->add('export_merge', 'Import request successfully sent. You will receive an email shortly with download link.');
//                } else {
//                    $this->get('session')->getFlashBag()->add('export_merge_error', 'File formate is not correct. Please try again.');
//                }
//            } else {
//                $this->get('session')->getFlashBag()->add('export_merge_error', 'File is empty. Please try again.');
//            }
//        } else {
//            $this->get('session')->getFlashBag()->add('export_merge_error', 'Select file that require to import. Please try again.');
//        }
//
//        return $this->redirect($this->generateUrl('record_list'));
    }

    /**
     * Import file 
     *
     * @Route("/import", name="import_record")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return array
     */
    public function importAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fileName = 'allFormat_1417781547.csv';
        $import = new ImportReport($this->container);
        $validation = $import->validateVocabulary($fileName);

        if ($validation) {
            echo '<pre>';
            foreach ($validation as $key => $value){
                echo '<p><b>'. str_replace('_', ' ', ucfirst($key)) . '</b><br />';
                echo implode('<br />', $value);
                echo '</p>';
            }
        }else{
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
    public function importRecordsAction(Request $request)
    {
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
                    $import = new ImportExport();
                    $import->setUser($this->getUser());
                    $import->setFormat($type);
                    $import->setType("import");
                    $import->setFileName($newFileName);
                    $import->setStatus(0);
                    $em->persist($import);
                    $em->flush();
//
//                    $job = new Job('avcc:import-records', array('id' => $import->getId()));
//                    $date = new DateTime();
//                    $date->add(new DateInterval('PT1M'));
//                    $job->setExecuteAfter($date);
//                    $em->persist($job);
//                    $em->flush($job);

                    $this->get('session')->getFlashBag()->add('import_success', 'Import request successfully sent. You will receive an email shortly with download link.');
                } else {
                    $this->get('session')->getFlashBag()->add('import_error', 'File formate is not correct. Please try again.');
                }
            } else {
                $this->get('session')->getFlashBag()->add('import_error', 'File is empty. Please try again.');
            }
        } else {
            $this->get('session')->getFlashBag()->add('import_error', 'Select file that require to import. Please try again.');
        }

        return $this->redirect($this->generateUrl('record_list'));
    }
}
