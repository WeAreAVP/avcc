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

namespace Application\Bundle\FrontBundle\Components;

use Symfony\Component\DependencyInjection\ContainerAware;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use Application\Bundle\FrontBundle\Helper\DefaultFields;
use Application\Bundle\FrontBundle\Entity\Records;
use Application\Bundle\FrontBundle\Entity\AudioRecords;
use Application\Bundle\FrontBundle\Entity\FilmRecords;
use Application\Bundle\FrontBundle\Entity\VideoRecords;
use PHPExcel_Style_NumberFormat as NumberFormat;

class ImportReport extends ContainerAware {

    public $columns;
    public $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function validateVocabulary($fileName, $organizationId = 0) {
        $fileCompletePath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/' . $fileName;
//        $fileCompletePath = '/Applications/XAMPP/xamppfiles/htdocs/avcc/web/' . $fileName;
        if (file_exists($fileCompletePath)) {
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($fileCompletePath);
            $invalidValues = null;
            $fields = new DefaultFields();
            $em = $this->container->get('doctrine')->getEntityManager();
            $vocabularies = $fields->getAllVocabularies($em);
            $projects = $em->getRepository('ApplicationFrontBundle:Projects')->getAllAsArray();
            $requiredMissing = false;
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                foreach ($worksheet->getRowIterator() as $_row) {
                    $row = $_row->getRowIndex();
                    if ($row > 1) {
                        $project = $worksheet->getCellByColumnAndRow(0, $row);
                        $collectionName = $worksheet->getCellByColumnAndRow(1, $row);
                        $mediaType = $worksheet->getCellByColumnAndRow(2, $row);
                        $uniqueId = $worksheet->getCellByColumnAndRow(3, $row);
                        $location = $worksheet->getCellByColumnAndRow(5, $row);
                        $format = $worksheet->getCellByColumnAndRow(6, $row);
                        $title = $worksheet->getCellByColumnAndRow(7, $row);
                        $description = $worksheet->getCellByColumnAndRow(8, $row);
                        $commercial = $worksheet->getCellByColumnAndRow(9, $row);
                        $base = $worksheet->getCellByColumnAndRow(14, $row);
                        $printType = $worksheet->getCellByColumnAndRow(15, $row);
                        $diskDiameter = $worksheet->getCellByColumnAndRow(16, $row);
                        $reelDiameter = $worksheet->getCellByColumnAndRow(17, $row);
                        $mediaDiameter = $worksheet->getCellByColumnAndRow(18, $row);
                        $recordingSpeed = $worksheet->getCellByColumnAndRow(20, $row);
                        $color = $worksheet->getCellByColumnAndRow(21, $row);
                        $tapeThickness = $worksheet->getCellByColumnAndRow(22, $row);
                        $sides = $worksheet->getCellByColumnAndRow(23, $row);
                        $trackType = $worksheet->getCellByColumnAndRow(24, $row);
                        $monoOrStereo = $worksheet->getCellByColumnAndRow(25, $row);
                        $noiseReduction = $worksheet->getCellByColumnAndRow(26, $row);
                        $cassetteSize = $worksheet->getCellByColumnAndRow(27, $row);
                        $formatVersion = $worksheet->getCellByColumnAndRow(28, $row);
                        $recordingStandard = $worksheet->getCellByColumnAndRow(29, $row);
                        $reelOrCore = $worksheet->getCellByColumnAndRow(30, $row);
                        $sound = $worksheet->getCellByColumnAndRow(31, $row);
                        $frameRate = $worksheet->getCellByColumnAndRow(33, $row);
                        $acidDetectionStrip = $worksheet->getCellByColumnAndRow(34, $row);
                        // do something
                        //   $uniqueids = $em->getRepository('ApplicationFrontBundle:Records')->findAllUniqueIds();
                        $uniqueids = $this->checkUniqueId($organizationId, $uniqueId->getValue());
                        if (trim($location->getValue()) == '') {
                            $invalidValues['missing_fields'][] = 'Location missing at row ' . $row;
                        }
//                        if (trim($collectionName->getValue()) == '') {
//                            $invalidValues['missing_fields'][] = 'Collection name missing at row ' . $row;
//                        }
//                        if (trim($description->getValue()) == '') {
//                            $invalidValues['missing_fields'][] = 'Description missing at row ' . $row;
//                        }
                        if (trim($title->getValue()) == '') {
                            $invalidValues['missing_fields'][] = 'Title missing at row ' . $row;
                        }
                        if (trim($project->getValue()) != '' && !in_array($project->getValue(), $projects)) {
                            $invalidValues['project_names'][] = $project->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if (trim($project->getValue()) == '') {
                            $invalidValues['missing_fields'][] = 'Project name missing at row ' . $row;
                        }
                        if (trim($mediaType->getValue()) != '' && !in_array($mediaType->getValue(), $vocabularies['mediaTypes'])) {
                            $invalidValues['media_types'][] = $mediaType->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if (trim($mediaType->getValue()) == '') {
                            $invalidValues['missing_fields'][] = 'Media type missing at row ' . $row;
                        }

                        if ($uniqueId->getValue() && $uniqueids != 0) {
                            $invalidValues['unique_ids'][] = $uniqueId->getValue() . ' at row ' . $row . ' already exist in db';
                        }
                        if (trim($uniqueId->getValue()) == '') {
                            $invalidValues['missing_fields'][] = 'Unique missing at ' . $row;
                        }
                        if ($format->getValue() && !in_array($format->getValue(), $vocabularies['formats'])) {
                            $invalidValues['formats'][] = $format->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if (trim($format->getValue()) == '') {
                            $invalidValues['missing_fields'][] = 'Format missing at row ' . $row;
                        }
                        if ($commercial->getValue() && !in_array($commercial->getValue(), $vocabularies['commercial'])) {
                            $invalidValues['commercial'][] = $commercial->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($base->getValue() && !in_array($base->getValue(), $vocabularies['bases'])) {
                            $invalidValues['bases'][] = $base->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($printType->getValue() && !in_array($printType->getValue(), $vocabularies['printTypes'])) {
                            $invalidValues['print_types'][] = $printType->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($diskDiameter->getValue() && !in_array($diskDiameter->getValue(), $vocabularies['diskDiameters'])) {
                            $invalidValues['disk_diameters'][] = $diskDiameter->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($reelDiameter->getValue() && !in_array($reelDiameter->getValue(), $vocabularies['reelDiameters'])) {
                            $invalidValues['reel_diameters'][] = $reelDiameter->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        $nf = new NumberFormat();
                        $mdValue = $nf->toFormattedString($mediaDiameter->getValue(), '0%');
                        if ($mediaDiameter->getValue() && !in_array($mdValue, $vocabularies['mediaDiameters'])) {
                            $invalidValues['media_diameters'][] = $mediaDiameter->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($recordingSpeed->getValue() && !in_array($recordingSpeed->getValue(), $vocabularies['recordingSpeed'])) {
                            $invalidValues['recording_speed'][] = $recordingSpeed->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($color->getValue() && !in_array($color->getValue(), $vocabularies['colors'])) {
                            $invalidValues['colors'][] = $color->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($tapeThickness->getValue() && !in_array($tapeThickness->getValue(), $vocabularies['tapeThickness'])) {
                            $invalidValues['tape_thickness'][] = $tapeThickness->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($sides->getValue() && !in_array($sides->getValue(), $vocabularies['sides'])) {
                            $invalidValues['sides'][] = $sides->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($trackType->getValue() && !in_array($trackType->getValue(), $vocabularies['trackTypes'])) {
                            $invalidValues['track_types'][] = $trackType->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($monoOrStereo->getValue() && !in_array($monoOrStereo->getValue(), $vocabularies['monoStereo'])) {
                            $invalidValues['mono_or_stereo'][] = $monoOrStereo->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($noiseReduction->getValue() && !in_array($noiseReduction->getValue(), $vocabularies['noiseReduction'])) {
                            $invalidValues['noise_reduction'][] = $noiseReduction->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($cassetteSize->getValue() && !in_array($cassetteSize->getValue(), $vocabularies['cassetteSizes'])) {
                            $invalidValues['cassette_sizes'][] = $cassetteSize->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($formatVersion->getValue() && !in_array($formatVersion->getValue(), $vocabularies['formatVersions'])) {
                            $invalidValues['format_versions'][] = $formatVersion->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($recordingStandard->getValue() && !in_array($recordingStandard->getValue(), $vocabularies['recordingStandards'])) {
                            $invalidValues['recording_standards'][] = $recordingStandard->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($reelOrCore->getValue() && !in_array($reelOrCore->getValue(), $vocabularies['reelCore'])) {
                            $invalidValues['reel_or_core'][] = $reelOrCore->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($sound->getValue() && !in_array($sound->getValue(), $vocabularies['sounds'])) {
                            $invalidValues['sounds'][] = $sound->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($frameRate->getValue() && !in_array($frameRate->getValue(), $vocabularies['frameRates'])) {
                            $invalidValues['frame_rates'][] = $frameRate->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                        if ($acidDetectionStrip->getValue() && !in_array($acidDetectionStrip->getValue(), $vocabularies['acidDetectionStrips'])) {
                            $invalidValues['acid_detection_strips'][] = $acidDetectionStrip->getValue() . ' at row ' . $row . ' not exist in db';
                        }
                    }
                }
            }
            return $invalidValues;
        } else {
            return 'file not found';
        }
    }

    public function getTotalRows($fileName) {
        $fileCompletePath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/' . $fileName;
        if (file_exists($fileCompletePath)) {
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($fileCompletePath);
            return $phpExcelObject->setActiveSheetIndex(0)->getHighestRow();
        }
    }

    public function getRecordsFromFile($fileName, $user) {
        $fileCompletePath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/' . $fileName;
//        $fileCompletePath = '/Applications/XAMPP/xamppfiles/htdocs/avcc/web/' . $fileName;
        if (file_exists($fileCompletePath)) {
            $formats = array();
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($fileCompletePath);
            $invalidValues = null;
            $fields = new DefaultFields();
            $em = $this->container->get('doctrine')->getEntityManager();
            $rows = array();
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                foreach ($worksheet->getRowIterator() as $_row) {
                    $row = $_row->getRowIndex();
                    if ($row > 1) {
                        $rows[$row - 1]['project'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $rows[$row - 1]['collectionName'] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $rows[$row - 1]['mediaType'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $rows[$row - 1]['uniqueId'] = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $rows[$row - 1]['alternateId'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $rows[$row - 1]['location'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $rows[$row - 1]['format'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $formats[] = $worksheet->getCellByColumnAndRow(2, $row)->getValue() . ' | ' . $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $rows[$row - 1]['title'] = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $rows[$row - 1]['description'] = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $rows[$row - 1]['commercial'] = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $rows[$row - 1]['contentDuration'] = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $rows[$row - 1]['mediaDuration'] = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $rows[$row - 1]['creationDate'] = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                        $rows[$row - 1]['contentDate'] = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                        $rows[$row - 1]['base'] = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                        $rows[$row - 1]['printType'] = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                        $rows[$row - 1]['diskDiameter'] = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                        $rows[$row - 1]['reelDiameter'] = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                        $md = $worksheet->getCellByColumnAndRow(18, $row);
                        $nf = new NumberFormat();
                        $mdValue = $nf->toFormattedString($md->getValue(), '0%');
                        $rows[$row - 1]['mediaDiameter'] = $mdValue;
                        $rows[$row - 1]['footage'] = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                        $rows[$row - 1]['recordingSpeed'] = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                        $rows[$row - 1]['color'] = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                        $rows[$row - 1]['tapeThickness'] = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                        $rows[$row - 1]['sides'] = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                        $rows[$row - 1]['trackType'] = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                        $rows[$row - 1]['monoOrStereo'] = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                        $rows[$row - 1]['noiseReduction'] = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                        $rows[$row - 1]['cassetteSize'] = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                        $rows[$row - 1]['formatVersion'] = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                        $rows[$row - 1]['recordingStandard'] = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                        $rows[$row - 1]['reelOrCore'] = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                        $rows[$row - 1]['sound'] = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                        $rows[$row - 1]['edgeCodeYear'] = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                        $rows[$row - 1]['frameRate'] = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                        $rows[$row - 1]['acidDetectionStrip'] = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                        $rows[$row - 1]['shrinkage'] = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                        $rows[$row - 1]['genreTerms'] = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                        $rows[$row - 1]['contributor'] = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                        $rows[$row - 1]['generation'] = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                        $rows[$row - 1]['part'] = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                        $rows[$row - 1]['copyright'] = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                        $rows[$row - 1]['duplicates'] = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                        $rows[$row - 1]['relatedMaterial'] = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                        $rows[$row - 1]['conditionNote'] = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                        $rows[$row - 1]['generalNote'] = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    }
                }
            }

            if ($rows) {
                $validation = $this->validateFormat($em, array_unique($formats));
                if (empty($validation)) {
                    return $this->importRecords($rows, $user, $em);
                } else {
                    return array('errors' => $validation);
                }
            }
        } else {
            return 'file not found';
        }
    }

    public function validateFormat($em, $formats) {
        $errors = array();
        foreach ($formats as $format) {
            $value = explode(" | ", $format);
            $mediaType = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('name' => $value[0]));
            $_format = $em->getRepository('ApplicationFrontBundle:Formats')->findOneBy(array('name' => $value[1], 'mediaType' => $mediaType->getId()));
            if (!$_format) {
                $errors[] = $format;
            }
        }
        return $errors;
    }

    public function importRecords($rows, $user, $em) {
        foreach ($rows as $row) {
            $record = new Records();
            $project = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('name' => $row['project']));
            $record->setProject($project);
            $record->setCollectionName($row['collectionName']);

            $mediaType = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('name' => $row['mediaType']));
            $record->setMediaType($mediaType);

            $record->setUniqueId($row['uniqueId']);
            $record->setAlternateId($row['alternateId']);
            $record->setLocation($row['location']);

            $format = $em->getRepository('ApplicationFrontBundle:Formats')->findOneBy(array('name' => $row['format'], 'mediaType' => $mediaType->getId()));
            $record->setFormat($format);

            $record->setTitle($row['title']);
            $record->setDescription($row['description']);
            if ($row['commercial']) {
                $commercial = $em->getRepository('ApplicationFrontBundle:Commercial')->findOneBy(array('name' => $row['commercial']));
                $record->setCommercial($commercial);
            }
            $record->setContentDuration($row['contentDuration']);
            $record->setCreationDate($row['creationDate']);
            $record->setContentDate($row['contentDate']);
            if ($row['reelDiameter']) {
                $reel = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->findOneBy(array('name' => $row['reelDiameter']));
                $record->setReelDiameters($reel);
            }

            $record->setGenreTerms($row['genreTerms']);
            $record->setContributor($row['contributor']);
            $record->setGeneration($row['generation']);
            $record->setPart($row['part']);
            $record->setCopyrightRestrictions($row['copyright']);
            $record->setDuplicatesDerivatives($row['duplicates']);
            $record->setRelatedMaterial($row['relatedMaterial']);
            $record->setConditionNote($row['conditionNote']);
            $record->setGeneralNote($row['generalNote']);
            $record->setCreatedOnValue(date('Y-m-d H:i:s'));
            $record->setUser($user);
            $em->persist($record);
            $em->flush();
            if ($row['mediaType'] == 'Audio') {
                $audio = new AudioRecords();
                $audio->setMediaDuration($row['mediaDuration']);
                if ($row['base']) {
                    $base = $em->getRepository('ApplicationFrontBundle:Bases')->findOneBy(array('name' => $row['base']));
                    $audio->setBases($base);
                }
                if ($row['diskDiameter']) {
                    $diskDiameter = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->findOneBy(array('name' => $row['diskDiameter']));
                    $audio->setDiskDiameters($diskDiameter);
                }
                if ($row['mediaDiameter']) {
                    $mediaD = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->findOneBy(array('name' => $row['mediaDiameter']));
                    $audio->setMediaDiameters($mediaD);
                }
                if ($row['tapeThickness']) {
                    $tapeThickness = $em->getRepository('ApplicationFrontBundle:TapeThickness')->findOneBy(array('name' => $row['tapeThickness']));
                    $audio->setTapeThickness($tapeThickness);
                }
                if ($row['sides']) {
                    $side = $em->getRepository('ApplicationFrontBundle:Slides')->findOneBy(array('name' => $row['sides']));
                    $audio->setSlides($side);
                }
                if ($row['trackType']) {
                    $trackType = $em->getRepository('ApplicationFrontBundle:TrackTypes')->findOneBy(array('name' => $row['trackType']));
                    $audio->setTrackTypes($trackType);
                }
                if ($row['monoOrStereo']) {
                    $monostereo = $em->getRepository('ApplicationFrontBundle:MonoStereo')->findOneBy(array('name' => $row['monoOrStereo']));
                    $audio->setMonoStereo($monostereo);
                }
                if ($row['noiseReduction']) {
                    $noise = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->findOneBy(array('name' => $row['noiseReduction']));
                    $audio->setNoiceReduction($noise);
                }
                $audio->setRecord($record);
                $em->persist($audio);
                $em->flush();
            }
            if ($row['mediaType'] == 'Film') {
                $filmRecord = new FilmRecords();
                if ($row['printType']) {
                    $printType = $em->getRepository('ApplicationFrontBundle:PrintTypes')->findOneBy(array('name' => $row['printType']));
                    $filmRecord->setPrintType($printType);
                }
                $filmRecord->setFootage($row['footage']);
                if ($row['color']) {
                    $color = $em->getRepository('ApplicationFrontBundle:Colors')->findOneBy(array('name' => $row['color']));
                    $filmRecord->setColors($color);
                }
                if ($row['reelOrCore']) {
                    $reelOrCore = $em->getRepository('ApplicationFrontBundle:ReelCore')->findOneBy(array('name' => $row['reelOrCore']));
                    $filmRecord->setReelCore($reelOrCore);
                }
                if ($row['sound']) {
                    $sound = $em->getRepository('ApplicationFrontBundle:Sounds')->findOneBy(array('name' => $row['sound']));
                    $filmRecord->setSound($sound);
                }
                if ($row['frameRate']) {
                    $frameRates = $em->getRepository('ApplicationFrontBundle:FrameRates')->findOneBy(array('name' => $row['frameRate']));
                    $filmRecord->setFrameRate($frameRates);
                }
                if ($row['acidDetectionStrip']) {
                    $strip = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->findOneBy(array('name' => $row['acidDetectionStrip']));
                    $filmRecord->setAcidDetectionStrip($strip);
                }
                $filmRecord->setShrinkage($row['shrinkage']);
                $filmRecord->setEdgeCodeYear($row['edgeCodeYear']);
                $filmRecord->setRecord($record);
                $em->persist($filmRecord);
                $em->flush();
            }
            if ($row['mediaType'] == 'Video') {
                $videoRecord = new VideoRecords();
                if ($row['recordingSpeed']) {
                    $speed = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->findOneBy(array('name' => $row['recordingSpeed']));
                    $videoRecord->setRecordingSpeed($speed);
                }
                if ($row['cassetteSize']) {
                    $cassette = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->findOneBy(array('name' => $row['cassetteSize']));
                    $videoRecord->setCassetteSize($cassette);
                }
                if ($row['formatVersion']) {
                    $formatVersion = $em->getRepository('ApplicationFrontBundle:FormatVersions')->findOneBy(array('name' => $row['formatVersion']));
                    $videoRecord->setFormatVersion($formatVersion);
                }
                if ($row['recordingStandard']) {
                    $recordingStandard = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->findOneBy(array('name' => $row['recordingStandard']));
                    $videoRecord->setRecordingStandard($recordingStandard);
                }
                $videoRecord->setRecord($record);
                $em->persist($videoRecord);
                $em->flush();
            }
            $shpinxInfo = $this->getSphinxInfo();
            $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $record->getId());
            $sphinxSearch->insert();
        }

        return count($rows);
    }

    /**
     * Get sphinx parameters
     *
     * @return array
     */
    protected function getSphinxInfo() {
        return $this->container->getParameter('sphinx_param');
    }

    protected function checkUniqueId($orgId, $uniqueId, $id = 0) {
        $em = $this->container->get('doctrine')->getEntityManager();
        $records = $em->getRepository('ApplicationFrontBundle:Records')->findOrganizationUniqueRecords($orgId, $uniqueId, $id);
        return count($records);
    }

}
