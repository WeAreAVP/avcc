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

    public function validateVocabulary($fileName, $organizationId = 0, $uniqueCheck = false) {
        $fileCompletePath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/' . $fileName;
        if (file_exists($fileCompletePath)) {
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($fileCompletePath);
            $invalidValues = null;
            $fields = new DefaultFields();
            $em = $this->container->get('doctrine')->getEntityManager();
            $vocabularies = $fields->getAllVocabularies($em);
            $projects = $em->getRepository('ApplicationFrontBundle:Projects')->getAllAsArray($organizationId);
           
            $requiredMissing = false;
            $unique = array();
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                foreach ($worksheet->getRowIterator() as $_row) {
                    $row = $_row->getRowIndex();
                    if ($row > 1) {
                        $project = $worksheet->getCellByColumnAndRow(0, $row);
                        if (!empty(trim($project)) && $project != null) {
                            $parentCollection = $worksheet->getCellByColumnAndRow(1, $row);
                            $collectionName = $worksheet->getCellByColumnAndRow(2, $row);
                            $mediaType = $worksheet->getCellByColumnAndRow(3, $row);
                            $location = $worksheet->getCellByColumnAndRow(6, $row);
                            $format = $worksheet->getCellByColumnAndRow(7, $row);
                            $title = $worksheet->getCellByColumnAndRow(8, $row);
                            $description = $worksheet->getCellByColumnAndRow(9, $row);
                            $commercial = $worksheet->getCellByColumnAndRow(10, $row);
                            $base = $worksheet->getCellByColumnAndRow(15, $row);
                            $printType = $worksheet->getCellByColumnAndRow(16, $row);
                            $diskDiameter = $worksheet->getCellByColumnAndRow(17, $row);
                            $reelDiameter = $worksheet->getCellByColumnAndRow(18, $row);
                            $mediaDiameter = $worksheet->getCellByColumnAndRow(19, $row);
                            $recordingSpeed = $worksheet->getCellByColumnAndRow(21, $row);
                            $color = $worksheet->getCellByColumnAndRow(22, $row);
                            $tapeThickness = $worksheet->getCellByColumnAndRow(23, $row);
                            $sides = $worksheet->getCellByColumnAndRow(24, $row);
                            $trackType = $worksheet->getCellByColumnAndRow(25, $row);
                            $monoOrStereo = $worksheet->getCellByColumnAndRow(26, $row);
                            $noiseReduction = $worksheet->getCellByColumnAndRow(27, $row);
                            $cassetteSize = $worksheet->getCellByColumnAndRow(28, $row);
                            $formatVersion = $worksheet->getCellByColumnAndRow(29, $row);
                            $recordingStandard = $worksheet->getCellByColumnAndRow(30, $row);
                            $reelOrCore = $worksheet->getCellByColumnAndRow(31, $row);
                            $sound = $worksheet->getCellByColumnAndRow(32, $row);
                            $frameRate = $worksheet->getCellByColumnAndRow(34, $row);
                            $acidDetectionStrip = $worksheet->getCellByColumnAndRow(35, $row);
                            $uniqueId = $worksheet->getCellByColumnAndRow(4, $row);
                            if (!$uniqueCheck) {
                                $uniqueids = $this->checkUniqueId($organizationId, $uniqueId->getValue());
                                if ($uniqueId->getValue() && $uniqueids != 0) {
                                    $invalidValues['unique_ids'][] = $uniqueId->getValue() . ' at row ' . $row . ' already exist in db';
                                }
                            }
                            if (trim($uniqueId->getValue()) == '') {
                                $invalidValues['missing_fields'][] = 'Unique missing at ' . $row;
                            }
                            if (trim($location->getValue()) == '') {
                                $invalidValues['missing_fields'][] = 'Location missing at row ' . $row;
                            }

                            if (!empty($uniqueId) && in_array($uniqueId, $unique)) {
                                $invalidValues['unique_ids'][] = $uniqueId->getValue() . ' at row ' . $row . ' already exist in import file';
                            } else if (!empty($uniqueId)) {
                                $unique[] = $uniqueId;
                            }
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

                            if ($parentCollection->getValue() && !in_array($parentCollection->getValue(), $vocabularies['parentCollection'])) {
                                $invalidValues['parentCollection'][] = $parentCollection->getValue() . ' at row ' . $row . ' not exist in db';
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
                            if (!in_array($mediaType, array("film", "Film"))) {
                                $mdValue = $nf->toFormattedString($mediaDiameter->getValue(), '0%');
                                if ($mediaDiameter->getValue() && !in_array($mdValue, $vocabularies['mediaDiameters'])) {
                                    $invalidValues['media_diameters'][] = $mediaDiameter->getValue() . ' at row ' . $row . ' not exist in db';
                                }
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
                break;
            }
            return $invalidValues;
        } else {
            return 'file not found';
        }
    }

    public function getTotalRows($fileName) {
        $counter = 0;
        $fileCompletePath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/' . $fileName;
        if (file_exists($fileCompletePath)) {
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($fileCompletePath);
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                foreach ($worksheet->getRowIterator() as $_row) {
                    $row = $_row->getRowIndex();
                    if ($row > 1) {
                        $project = $worksheet->getCellByColumnAndRow(0, $row);
                        if (!empty(trim($project)) && $project != null) {
                            $counter++;
                        }
                    }
                }
                break;
            }
            return $counter;
        }
    }

    public function getRecordsFromFile($fileName, $user, $insertType = false, $org_id = false) {
        $fileCompletePath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/' . $fileName;
//        $fileCompletePath = '/Applications/XAMPP/xamppfiles/htdocs/avcc/web/' . $fileName;
        if (file_exists($fileCompletePath)) {
            $formats = array();
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($fileCompletePath);
            $invalidValues = null;
            $fields = new DefaultFields();
            $em = $this->container->get('doctrine')->getEntityManager();
            $rows = array();
            $errors = [];
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                foreach ($worksheet->getRowIterator() as $_row) {
                    $row = $_row->getRowIndex();
                    if ($row > 1) {
                        $media_type = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        if (!empty(trim($media_type)) && $media_type != null) {
                            $rows[$row - 1]['project'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                            $rows[$row - 1]['parentCollection'] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            $rows[$row - 1]['collectionName'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                            $rows[$row - 1]['mediaType'] = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                            $rows[$row - 1]['uniqueId'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                            $rows[$row - 1]['alternateId'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                            $rows[$row - 1]['location'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                            $rows[$row - 1]['format'] = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                            $formats[] = $worksheet->getCellByColumnAndRow(3, $row)->getValue() . ' | ' . $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                            $rows[$row - 1]['title'] = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                            $rows[$row - 1]['description'] = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $rows[$row - 1]['commercial'] = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $rows[$row - 1]['contentDuration'] = $worksheet->getCellByColumnAndRow(11, $row)->getFormattedValue();
                            if(empty($rows[$row - 1]['contentDuration'])){
                                $rows[$row - 1]['contentDuration'] = null;
                            } else if(substr_count($rows[$row - 1]['contentDuration'], '.') > 1) {
                                $errors[] = 'Content Duration value ' . $rows[$row - 1]['contentDuration'] . ' at row ' . $row . ' is not valid. It should be float or h:m:s';
                            }
                            $rows[$row - 1]['mediaDuration'] = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                            $rows[$row - 1]['creationDate'] = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                            $rows[$row - 1]['contentDate'] = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                            $rows[$row - 1]['base'] = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                            $rows[$row - 1]['printType'] = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                            $rows[$row - 1]['diskDiameter'] = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                            $rows[$row - 1]['reelDiameter'] = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                            $md = $worksheet->getCellByColumnAndRow(19, $row);
                            $nf = new NumberFormat();
                            $mdValue = $nf->toFormattedString($md->getValue(), '0%');
                            if (in_array($media_type, array("film", "Film"))) {
                                $rows[$row - 1]['mediaDiameter'] = $md->getValue();
                            } else {
                                $rows[$row - 1]['mediaDiameter'] = $mdValue;
                            }
                            $rows[$row - 1]['footage'] = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                            $rows[$row - 1]['recordingSpeed'] = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                            $rows[$row - 1]['color'] = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                            $rows[$row - 1]['tapeThickness'] = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                            $rows[$row - 1]['sides'] = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                            $rows[$row - 1]['trackType'] = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                            $rows[$row - 1]['monoOrStereo'] = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                            $rows[$row - 1]['noiseReduction'] = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                            $rows[$row - 1]['cassetteSize'] = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                            $rows[$row - 1]['formatVersion'] = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                            $rows[$row - 1]['recordingStandard'] = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                            $rows[$row - 1]['reelOrCore'] = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                            $rows[$row - 1]['sound'] = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                            $rows[$row - 1]['edgeCodeYear'] = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                            $rows[$row - 1]['frameRate'] = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                            $rows[$row - 1]['acidDetectionStrip'] = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                            $rows[$row - 1]['shrinkage'] = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                            $rows[$row - 1]['genreTerms'] = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                            $rows[$row - 1]['contributor'] = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                            $rows[$row - 1]['generation'] = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                            $rows[$row - 1]['part'] = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                            $rows[$row - 1]['copyright'] = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                            $rows[$row - 1]['duplicates'] = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                            $rows[$row - 1]['relatedMaterial'] = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                            $rows[$row - 1]['conditionNote'] = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                            $rows[$row - 1]['generalNote'] = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
// here
                            $rows[$row - 1]['isReview'] = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                            $rows[$row - 1]['reformattingPriority'] = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                            $rows[$row - 1]['transcription'] = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                            $rows[$row - 1]['digitized'] = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                            $rows[$row - 1]['digitizedBy'] = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                            $rows[$row - 1]['digitizedWhen'] = $worksheet->getCellByColumnAndRow(51, $row)->getFormattedValue(); //$worksheet->getCellByColumnAndRow(51, $row)->getValue();
                            $rows[$row - 1]['urn'] = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                            $rows[$row - 1]['accessLevel'] = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                        }
                    }
                }
                break;
            }
            
            if ($rows) {
                $validation = $this->validateFormat($em, array_unique($formats));
                if (empty($validation) && empty($errors)) {
                    return $this->importRecords($rows, $user, $em, $insertType, $org_id);
                } else if(!empty($errors)) {
                    return array('errors' => array('validation' => $errors));
                }else {
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

    public function importRecords($rows, $user, $em, $insertType = false, $org_id = false) {
        $countt = 0;
        foreach ($rows as $row) {
            if (!empty($row['uniqueId'])) {
                $project = array();
                if ($org_id) {
                    $project = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('name' => $row['project'], 'organization' => $org_id));
                } else {
                    $project = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('name' => $row['project']));
                }
                
                $result = $em->getRepository('ApplicationFrontBundle:Records')->findOrganizationUniqueRecords($project->getOrganization()->getId(), $row['uniqueId'], 0);

                if ($insertType == 1) {
                    if (count($result) > 0) {
                        continue;
                    }
                }

                if ($insertType == 2 && count($result) > 0) {
                    $_id = $result[0]['id'];

                    $record = $em->getRepository('ApplicationFrontBundle:Records')->find($_id);
                } else {
                    $record = new Records();
                }

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
                $record->setContentDuration($this->convertTimeToMinutes($row['contentDuration']));
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

                if (strtolower($row['isReview']) == 'yes') {
                    $record->setIsReview(1);
                } else {
                    $record->setIsReview(0);
                }
                if (strtolower($row['reformattingPriority']) == 'yes') {
                    $record->setReformattingPriority(1);
                } else {
                    $record->setReformattingPriority(0);
                }
                if (strtolower($row['transcription']) == 'yes') {
                    $record->setTranscription(1);
                } else {
                    $record->setTranscription(0);
                }
                if (strtolower($row['digitized']) == 'yes') {
                    $record->setDigitized(1);
                    $record->setDigitizedBy($row['digitizedBy']);
                    $record->setDigitizedWhen($row['digitizedWhen']);
                    $record->setUrn($row['urn']);
                } else {
                    $record->setDigitized(0);
                    $record->setDigitizedBy("");
                    $record->setDigitizedWhen("");
                    $record->setUrn("");
                }
                $record->setAccessLevel($row['accessLevel']);
                if (!empty($row['parentCollection']) && $row['parentCollection'] != null) {
                    $parentCollection = $em->getRepository('ApplicationFrontBundle:ParentCollection')->findOneBy(array('name' => $row['parentCollection']));
                    $record->setParentCollection($parentCollection);
                }

                $record->setCreatedOnValue(date('Y-m-d H:i:s'));
                $record->setUser($user);
                $em->persist($record);
                $em->flush($record);
                if ($row['mediaType'] == 'Audio') {
                    if ($insertType == 2 && count($result) > 0) {
                        $audio = $em->getRepository('ApplicationFrontBundle:AudioRecords')->findOneBy(array('record' => $result[0]['id']));
                    } else {
                        $audio = new AudioRecords();
                    }
                    if ($audio != null) {
                        if ($row['mediaDuration'] != null && !empty($row['mediaDuration']))
                            $audio->setMediaDuration($row['mediaDuration']);
                        if ($row['recordingSpeed']) {
                            $speed = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->findOneBy(array('name' => $row['recordingSpeed']));
                            $audio->setRecordingSpeed($speed);
                        }
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
                        $em->flush($audio);
                    }
                }
                if ($row['mediaType'] == 'Film') {
                    if ($insertType == 2 && count($result) > 0) {
                        $filmRecord = $em->getRepository('ApplicationFrontBundle:FilmRecords')->findOneBy(array('record' => $result[0]['id']));
                    } else {
                        $filmRecord = new FilmRecords();
                    }

                    if ($filmRecord != null) {
                        if ($row['base']) {
                            $base = $em->getRepository('ApplicationFrontBundle:Bases')->findOneBy(array('name' => $row['base']));
                            $filmRecord->setBases($base);
                        }
                        if ($row['mediaDiameter']) {
                            $filmRecord->setMediaDiameter($row['mediaDiameter']);
                        }
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
                        $em->flush($filmRecord);
                    }
                }
                if ($row['mediaType'] == 'Video') {
                    if ($insertType == 2 && count($result) > 0) {
                        $videoRecord = $em->getRepository('ApplicationFrontBundle:VideoRecords')->findOneBy(array('record' => $result[0]['id']));
                    } else {
                        $videoRecord = new VideoRecords();
                    }
                    if ($videoRecord != null) {
                        if ($row['mediaDuration'] != null && !empty($row['mediaDuration'])) {
                            $videoRecord->setMediaDuration($row['mediaDuration']);
                        }
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
                        $em->flush($videoRecord);
                    }
                }
                $shpinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $record->getId());
                $sphinxSearch->replace();
                $countt++;
            }
        }

        return $countt;
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

    public function validateUniqueIdVocab($fileName, $organizationId = 0) {
        $fileCompletePath = $this->container->getParameter('webUrl') . 'import/' . date('Y') . '/' . date('m') . '/' . $fileName;
        if (file_exists($fileCompletePath)) {
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($fileCompletePath);
            $invalidValues = null;
            $unique = array();
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                foreach ($worksheet->getRowIterator() as $_row) {
                    $row = $_row->getRowIndex();
                    if ($row > 1) {
                        $uniqueId = $worksheet->getCellByColumnAndRow(4, $row);
                        $uniqueids = $this->checkUniqueId($organizationId, $uniqueId->getValue());
                        if ($uniqueId->getValue() && $uniqueids != 0) {
                            $invalidValues[] = $uniqueId->getValue() . ' at row ' . $row;
                        }
                    }
                }
                break;
            }
            return $invalidValues;
        } else {
            return 'file not found';
        }
    }

    private function convertTimeToMinutes($hms) {
        if ($hms != "") {
            $a = explode(":", $hms);
            if (count($a) > 1) {
                if (count($a) == 2 && (int) $a[1] == 60) {
                    $seconds = ((int) $a[0] * 60) + (int) $a[1];
                    $minutes = $seconds / 60;
                } else if (count($a) == 2) {
                    $minutes = (int) $a[0] + ((int) $a[1] / 100);
                } else if (count($a) == 3 && (int) $a[2] == 60) {
                    $seconds = ((int) $a[0] * 60 * 60) + ((int) $a[1] * 60) + (int) $a[2];
                    $minutes = $seconds / 60;
                } else {
                    $seconds = ((int) $a[0] * 60 * 60) + ((int) $a[1] * 60);
                    $minutes = ($seconds / 60) + ((int) $a[2] / 100);
                }
                return $minutes;
            }
        }
        return $hms;
    }

}
