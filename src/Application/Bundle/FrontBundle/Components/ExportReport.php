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
use Application\Bundle\FrontBundle\Helper\ExportFields;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use PHPExcel_Cell;

class ExportReport extends ContainerAware {

    public $columns;
    public $container;
    private $merge_header;

    public function __construct($container) {
        $this->container = $container;
        $this->merge_header = array();
    }

    public function prepareManifestReport($activeSheet, $records) {
        $row = 1;
        $columns = new ExportFields();
        $this->columns = $columns->getManifestColumns();
        foreach ($this->columns as $column => $columnName) {
            $activeSheet->setCellValueExplicitByColumnAndRow($column, $row, $columnName);
            $activeSheet->getColumnDimensionByColumn($column)->setWidth(20);
            $activeSheet->getStyleByColumnAndRow($column)->getAlignment()->setWrapText(true);
            $activeSheet->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        }
        $activeSheet->getRowDimension($row)->setRowHeight(50);
        $row++;

        foreach ($records as $record) {

            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record->getUniqueId());
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, ($record->getUser()->getOrganizations()) ? $record->getUser()->getOrganizations()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record->getCollectionName());
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, ($record->getFormat()->getName()) ? $record->getFormat()->getName() : '');
            $printType = '';
            if ($record->getFilmRecord()) {
                $printType = ($record->getFilmRecord()->getPrintType()) ? $record->getFilmRecord()->getPrintType()->getName() : '';
            }
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $printType);

            $mediaType = ($record->getReelDiameters()) ? $record->getReelDiameters()->getName() . "\n" : '';
            if ($record->getAudioRecord()) {
                $mediaType .=($record->getAudioRecord()->getDiskDiameters()) ? $record->getAudioRecord()->getDiskDiameters()->getName() . "\n" : '';
            }
            if ($record->getVideoRecord()) {
                $mediaType .=($record->getVideoRecord()->getCassetteSize()) ? $record->getVideoRecord()->getCassetteSize()->getName() . "\n" : '';
            }
            $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $mediaType);
            $activeSheet->getStyleByColumnAndRow(5, $row)->getAlignment()->setWrapText(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $record->getTitle());
            $duration = $record->getContentDuration();
            if (empty($duration) || $duration < 0) {
                if ($record->getAudioRecord()) {
                    $duration = ($record->getAudioRecord()->getMediaDuration()) ? $record->getAudioRecord()->getMediaDuration() : '';
                }
            }
            $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $duration);
            $row++;
        }
    }

    public function generateReport($records) {
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
                ->setTitle("AVCC - Report")
                ->setSubject("Report for all formats")
                ->setDescription("Report for all formats");
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('All Formats');
        $row = 1;
// Prepare header row for report
        $this->prepareHeader($activeSheet, $row);
        $row++;
        $this->prepareRecords($activeSheet, $row, $records);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }

    public function outputReport($type, $phpExcelObject, $fileStartName = 'allFormat') {
        $date = new \DateTime();
        $format = ($type == 'csv') ? 'CSV' : 'Excel2007';
        $writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, $format);
        $filename = $fileStartName . '_' . $date->format('Ymdhis') . '.' . $type;
        $response = $this->container->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment;filename={$filename}");
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

    /**
     * Save report on server.
     * @param  type   $type
     * @param  type   $phpExcelObject
     * @return string
     */
    public function saveReport($type, $phpExcelObject, $export_merge) {
        $date = new \DateTime();
        $format = ($type == 'csv') ? 'CSV' : 'Excel2007';
        $writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, $format);
        if ($export_merge == 1) {
            $filename = 'merge_' . $date->format('Ymdhis') . '.' . $type;
        } else if ($export_merge == 2) {
            $filename = 'export_' . $date->format('Ymdhis') . '.' . $type;
        }

        $folderPath = $this->container->getParameter('webUrl') . 'exports/' . date('Y') . '/' . date('m') . '/';
        $completePath = $folderPath . $filename;
        $downloadPath = 'exports/' . date('Y') . '/' . date('m') . '/' . $filename;
        if (!is_dir($folderPath))
            mkdir($folderPath, 0777, TRUE);

        $writer->save($completePath);

        return $downloadPath;
    }

    /**
     * Create the Header for report.
     *
     * @param  PHPExcel_Worksheet $activeSheet
     * @param  Integer            $row
     * @return boolean
     */
    private function prepareHeader($activeSheet, $row) {
        $columns = new ExportFields();
        $this->columns = $columns->getExportColumns();
        foreach ($this->columns as $column => $columnName) {
            $activeSheet->setCellValueExplicitByColumnAndRow($column, $row, str_replace('_', ' ', $columnName));
            $activeSheet->getColumnDimensionByColumn($column)->setWidth(20);
            $activeSheet->getStyleByColumnAndRow($column)->getFont()->setBold(true);
        }

        return TRUE;
    }

    /**
     * Prepare rows for records.
     *
     * @param  PHPExcel_Worksheet $activeSheet
     * @param  Integer            $row
     * @return boolean
     */
    private function prepareRecords($activeSheet, $row, $records) {
        foreach ($records as $record) {
            $this->makeExcelRows($activeSheet, $record, false, $row);
            $row++;
        }

        return true;
    }

    public function initReport() {
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
                ->setTitle("AVCC - Report")
                ->setSubject("Report for all formats")
                ->setDescription("Report for all formats");
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('All Formats');
        $row = 1;
// Prepare header row for report
        $this->prepareHeader($activeSheet, $row);

        return $phpExcelObject;
    }

    /**
     * Get records from sphinx
     *
     * @param type $user
     * @param type $sphinxInfo
     * @param type $sphinxCriteria
     * @param type $em
     *
     * @return type
     */
    public function fetchFromSphinx($user, $sphinxInfo, $sphinxCriteria, $em) {
        $phpExcelObject = $this->initReport();
        $row = 2;
        $count = 0;
        $offset = 0;
        $sphinxObj = new SphinxSearch($em, $sphinxInfo);
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);
        while ($count == 0) {
            $records = $sphinxObj->select($user, $offset, 1000, 'title', 'asc', $sphinxCriteria);
            $rec = $records[0];
            $totalFound = $records[1][1]['Value'];
            $this->prepareRecordsFromSphinx($activeSheet, $row, $rec);
            $offset = $offset + 1000;
            $total = count($records[0]);
            $row = $row + $total;
            if ((int) $total < 1000) {
                $count++;
            }
        }
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }

    /**
     * Prepare rows for records.
     *
     * @param  PHPExcel_Worksheet $activeSheet
     * @param  Integer            $row
     * @return boolean
     */
    private function prepareRecordsFromSphinx($activeSheet, $row, $records) {
        foreach ($records as $record) {
            $this->makeExcelRowsByArray($activeSheet, $record, false, $row);
            $row++;
        }
    }

    public function mergeRecords($records, $mergeToFile, $newphpExcelObject = null) {
        $mergeFileCompletePath = $this->container->getParameter('webUrl') . 'merge/' . date('Y') . '/' . date('m') . '/' . $mergeToFile;
        if (file_exists($mergeFileCompletePath)) {
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($mergeFileCompletePath);

            $new_header = $this->merge_header;
            $rows = array();
            $columnNames = array();
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) { // Loop all the worksheets.
                foreach ($worksheet->getRowIterator() as $row) { // Loop all the rows within worksheet.
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set.
                    foreach ($cellIterator as $cell) { // Loop all the columns within row.
                        if ($row->getRowIndex() == 1) { // Prepare header 
                            $cellValue = str_replace(' ', '_', $cell->getCalculatedValue());
                            $columnNames[$cell->getCoordinate()] = $cellValue;
                            $check = $this->checkHeader($cellValue);
                            if (strtolower($cellValue) == 'unique_id')
                                $uniqueIdIndex = PHPExcel_Cell::columnIndexFromString($cell->getColumn()) - 1;
                            if (!$check) {
                                $head[] = $cellValue;
                                $new_header[] = $cellValue;
                            } else if (strtolower($cellValue) != 'unique_id') {
                                $new_header[] = $cellValue;
                                $head[] = 'Ext_' . $cellValue;
                            }
                        } else { // manipulate rows other then header.
                            $columnName = strtolower($columnNames[$cell->getColumn() . '1']);
                            $columnValue = $cell->getCalculatedValue();
                            $uniq = strtolower(str_replace(' ', '_', $worksheet->getCellByColumnAndRow($uniqueIdIndex, $row->getRowIndex())->getValue()));
                            $rows[$uniq][$columnName] = $columnValue;
                        }
                    }
                }
            }
            if ($newphpExcelObject == null) {
                $this->merge_header = $new_header;
                $newphpExcelObject = $this->initMergeReport($head);
            }
            $newphpExcelObject->setActiveSheetIndex(0);
            $activeSheet = $newphpExcelObject->getActiveSheet();
            // Rimsha excel is now reading all the cell values. You need to fix code below this line.
            $newrow = 2;
            foreach ($records as $rec) {
                if (is_object($rec)) {
                    $recUniq = strtolower(str_replace(' ', '_', $rec->getUniqueId()));
                    if (array_key_exists($recUniq, $rows)) {
                        $this->makeExcelRows($activeSheet, $rec, $rows[$recUniq], $newrow, $new_header);
                        unset($rows[$recUniq]);
                    } else {
                        $this->makeExcelRows($activeSheet, $rec, false, $newrow, $new_header);
                    }
                } else {
                    $recUniq = strtolower(str_replace(' ', '_', $rec['unique_id']));
                    if (array_key_exists($recUniq, $rows)) {
                        $this->makeExcelRowsByArray($activeSheet, $rec, $rows[$recUniq], $newrow, $new_header);
                        unset($rows[$recUniq]);
                    } else {
                        $this->makeExcelRowsByArray($activeSheet, $rec, false, $newrow, $new_header);
                    }
                }
                $newrow++;
            }
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    $this->makeExcelRowsByArray($activeSheet, false, $row, $newrow, $new_header);
                    $newrow++;
                }
            }
            return $newphpExcelObject;
        } else {
            return "The file $mergeToFile does not exist";
        }
    }

    public function makeExcelRows($activeSheet, $record, $mergRow, $row, $new_header = null) {
        if ($record) {
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record->getProject());
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record->getCollectionName());
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record->getMediaType());
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $record->getUniqueId());
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $record->getAlternateId());
            $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $record->getLocation());
            $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, ($record->getFormat()->getName()) ? $record->getFormat()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $record->getTitle());
            $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $record->getDescription());
            $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, ($record->getCommercial()) ? $record->getCommercial()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $record->getContentDuration());
            $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $record->getCreationDate());
            $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, $record->getContentDate());
            $activeSheet->setCellValueExplicitByColumnAndRow(17, $row, ($record->getReelDiameters()) ? $record->getReelDiameters()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(36, $row, ($record->getGenreTerms()));
            $activeSheet->setCellValueExplicitByColumnAndRow(37, $row, ($record->getContributor()));
            $activeSheet->setCellValueExplicitByColumnAndRow(38, $row, $record->getGeneration());
            $activeSheet->setCellValueExplicitByColumnAndRow(39, $row, $record->getPart());
            $activeSheet->setCellValueExplicitByColumnAndRow(40, $row, $record->getCopyrightRestrictions());
            $activeSheet->setCellValueExplicitByColumnAndRow(41, $row, $record->getDuplicatesDerivatives());
            $activeSheet->setCellValueExplicitByColumnAndRow(42, $row, $record->getRelatedMaterial());
            $activeSheet->setCellValueExplicitByColumnAndRow(43, $row, $record->getConditionNote());
            $activeSheet->setCellValueExplicitByColumnAndRow(44, $row, $record->getGeneralNote());
            $activeSheet->setCellValueExplicitByColumnAndRow(45, $row, $record->getCreatedOn()->format('Y-m-d H:i:s'));
            $activeSheet->setCellValueExplicitByColumnAndRow(46, $row, ($record->getUpdatedOn()) ? $record->getUpdatedOn()->format('Y-m-d H:i:s') : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(47, $row, $record->getUser()->getName());

            if ($record->getAudioRecord()) {
                $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, ($record->getAudioRecord()->getMediaDuration()) ? $record->getAudioRecord()->getMediaDuration() : "");
                $activeSheet->setCellValueExplicitByColumnAndRow(14, $row, ($record->getAudioRecord()->getBases()) ? $record->getAudioRecord()->getBases()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(16, $row, ($record->getAudioRecord()->getDiskDiameters()) ? $record->getAudioRecord()->getDiskDiameters()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(18, $row, ($record->getAudioRecord()->getMediaDiameters()) ? $record->getAudioRecord()->getMediaDiameters()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(22, $row, ($record->getAudioRecord()->getTapeThickness()) ? $record->getAudioRecord()->getTapeThickness()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(23, $row, ($record->getAudioRecord()->getSlides()) ? $record->getAudioRecord()->getSlides()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(24, $row, ($record->getAudioRecord()->getTrackTypes()) ? $record->getAudioRecord()->getTrackTypes()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(25, $row, ($record->getAudioRecord()->getMonoStereo()) ? $record->getAudioRecord()->getMonoStereo()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(26, $row, ($record->getAudioRecord()->getNoiceReduction()) ? $record->getAudioRecord()->getNoiceReduction()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(20, $row, ($record->getAudioRecord()->getRecordingSpeed()) ? $record->getAudioRecord()->getRecordingSpeed()->getName() : '');
            }
            if ($record->getFilmRecord()) {
                $activeSheet->setCellValueExplicitByColumnAndRow(14, $row, ($record->getFilmRecord()->getBases()) ? $record->getFilmRecord()->getBases()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(15, $row, ($record->getFilmRecord()->getPrintType()) ? $record->getFilmRecord()->getPrintType()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(19, $row, ($record->getFilmRecord()->getFootage()) ? $record->getFilmRecord()->getFootage() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(21, $row, ($record->getFilmRecord()->getColors()) ? $record->getFilmRecord()->getColors()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(30, $row, ($record->getFilmRecord()->getReelCore()) ? $record->getFilmRecord()->getReelCore()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(31, $row, ($record->getFilmRecord()->getSound()) ? $record->getFilmRecord()->getSound()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(32, $row, ($record->getFilmRecord()->getEdgeCodeYear()) ? $record->getFilmRecord()->getEdgeCodeYear() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(33, $row, ($record->getFilmRecord()->getFrameRate()) ? $record->getFilmRecord()->getFrameRate()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(34, $row, ($record->getFilmRecord()->getAcidDetectionStrip()) ? $record->getFilmRecord()->getAcidDetectionStrip()->getName() : "");
                $activeSheet->setCellValueExplicitByColumnAndRow(35, $row, ($record->getFilmRecord()->getShrinkage()) ? $record->getFilmRecord()->getShrinkage() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(18, $row, ($record->getFilmRecord()->getMediaDiameter()) ? $record->getFilmRecord()->getMediaDiameter() : '');
            }
            if ($record->getVideoRecord()) {
                $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, ($record->getVideoRecord()->getMediaDuration()) ? $record->getVideoRecord()->getMediaDuration() : "");
                $activeSheet->setCellValueExplicitByColumnAndRow(20, $row, ($record->getVideoRecord()->getRecordingSpeed()) ? $record->getVideoRecord()->getRecordingSpeed()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(27, $row, ($record->getVideoRecord()->getCassetteSize()) ? $record->getVideoRecord()->getCassetteSize()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(28, $row, ($record->getVideoRecord()->getFormatVersion()) ? $record->getVideoRecord()->getFormatVersion()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(29, $row, ($record->getVideoRecord()->getRecordingStandard()) ? $record->getVideoRecord()->getRecordingStandard()->getName() : '');
            }
        }
        if ($mergRow) {
            $this->mergeRow($activeSheet, $mergRow, $row, $new_header);
        }
    }

    public function appendCellValuesByObject($record, $row) {
        $newRow = null;
        $newRow['project'] = $record->getProject() ? $record->getProject()->getName() : '';
        $newRow['collection_name'] = $record->getCollectionName();
        $newRow['media_type'] = $record->getMediaType() ? $record->getMediaType()->getName() : '';
        $newRow['unique_id'] = $record->getUniqueId();
        $newRow['location'] = $record->getLocation();
        $newRow['format'] = $record->getFormat()->getName() ? $record->getFormat()->getName() : '';
        $newRow['title'] = $record->getTitle();
        $newRow['description'] = $record->getDescription();
        $newRow['commercial'] = $record->getCommercial() ? $record->getCommercial()->getName() : '';
        $newRow['content_duration'] = $record->getContentDuration();
        $newRow['creation_date'] = $record->getCreationDate();
        $newRow['content_date'] = $record->getContentDate();
        $newRow['reel_diameter'] = $record->getReelDiameters() ? $record->getReelDiameters()->getName() : '';
        $newRow['genre_terms'] = $record->getGenreTerms();
        $newRow['contributor'] = $record->getContributor();
        $newRow['generation'] = $record->getGeneration();
        $newRow['part'] = $record->getPart();
        $newRow['copyright_restrictions'] = $record->getCopyrightRestrictions();
        $newRow['duplicates_derivatives'] = $record->getDuplicatesDerivatives();
        $newRow['related_material'] = $record->getRelatedMaterial();
        $newRow['condition_note'] = $record->getConditionNote();
        $newRow['created_on'] = $record->getCreatedOn()->format('Y-m-d H:i:s');
        $newRow['updated_on'] = $record->getUpdatedOn() ? $record->getUpdatedOn()->format('Y-m-d H:i:s') : '';
        $newRow['user_name'] = $record->getUser()->getName();

        if ($row['media_type'] == 'Audio') {
            $newRow['media_duration'] = $record->getAudioRecord()->getMediaDuration();
            $newRow['base'] = $record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "";
            $newRow['disk_diameter'] = $record->getAudioRecord()->getDiskDiameters() ? $record->getAudioRecord()->getDiskDiameters()->getName() : "";
            $newRow['media_diameter'] = $record->getAudioRecord()->getMediaDiameters() ? $record->getAudioRecord()->getMediaDiameters()->getName() : "";
            $newRow['tape_thickness'] = $record->getAudioRecord()->getTapeThickness() ? $record->getAudioRecord()->getTapeThickness()->getName() : "";
            $newRow['slides'] = $record->getAudioRecord()->getSlides() ? $record->getAudioRecord()->getSlides()->getName() : "";
            $newRow['track_type'] = $record->getAudioRecord()->getTrackTypes() ? $record->getAudioRecord()->getTrackTypes()->getName() : "";
            $newRow['mono_stereo'] = $record->getAudioRecord()->getMonoStereo() ? $record->getAudioRecord()->getMonoStereo()->getName() : "";
            $newRow['noice_reduction'] = $record->getAudioRecord()->getNoiceReduction() ? $record->getAudioRecord()->getNoiceReduction()->getName() : "";
        }
        if ($row['media_type'] == 'Film') {
            $newRow['print_type'] = $record->getFilmRecord()->getPrintType() ? $record->getFilmRecord()->getPrintType()->getName() : "";
            $newRow['footage'] = $record->getFilmRecord()->getFootage() ? $record->getFilmRecord()->getFootage()->getName() : "";
            $newRow['color'] = $record->getFilmRecord()->getColors() ? $record->getFilmRecord()->getColors()->getName() : "";
            $newRow['reel_core'] = $record->getFilmRecord()->getReelCore() ? $record->getFilmRecord()->getReelCore()->getName() : "";
            $newRow['sound'] = $record->getFilmRecord()->getSound() ? $record->getFilmRecord()->getSound()->getName() : "";
            $newRow['frame_rate'] = $record->getFilmRecord()->getFrameRate() ? $record->getFilmRecord()->getFrameRate()->getName() : "";
            $newRow['acid_detection'] = $record->getFilmRecord()->getAcidDetectionStrip() ? $record->getFilmRecord()->getAcidDetectionStrip()->getName() : "";
            $newRow['shrinkage'] = $record->getFilmRecord()->getShrinkage() ? $record->getFilmRecord()->getShrinkage()->getName() : "";
        }
        if ($row['media_type'] == 'Video') {
            $newRow['recording_speed'] = $record->getVideoRecord()->getRecordingSpeed() ? $record->getVideoRecord()->getRecordingSpeed()->getName() : "";
            $newRow['cassette_size'] = $record->getVideoRecord()->getCassetteSize() ? $record->getVideoRecord()->getCassetteSize()->getName() : "";
            $newRow['format_version'] = $record->getVideoRecord()->getFormatVersion() ? $record->getVideoRecord()->getFormatVersion()->getName() : "";
            $newRow['media_duration'] = $record->getVideoRecord()->getRecordingStandard() ? $record->getVideoRecord()->getRecordingStandard()->getName() : "";
        }
        $newRow['external_project'] = $row['project_name'];
        $newRow['external_collection_name'] = $row['collection_name'];
        $newRow['external_media_type'] = $row['media_type'];
        $newRow['external_unique_id'] = $row['unique_id'];
        $newRow['external_location'] = $row['location'];
        $newRow['external_format'] = $row['format'];
        $newRow['external_title'] = $row['title'];
        $newRow['external_description'] = $row['description'];
        $newRow['external_commercial'] = $row['commercial_or_unique'];
        $newRow['external_content_duration'] = $row['content_duration'];
        $newRow['external_creation_date'] = $row['creation_date'];
        $newRow['external_content_date'] = $row['content_date'];
        $newRow['external_reel_diameter'] = $row['reel_diameter'];
        $newRow['external_genre_terms'] = $row['genre_terms'];
        $newRow['external_contributor'] = $row['contributor'];
        $newRow['external_generation'] = $row['generation'];
        $newRow['external_part'] = $row['part'];
        $newRow['external_copyright_restrictions'] = $row['copyright_/_restrictions'];
        $newRow['external_duplicates_derivatives'] = $row['duplicates_/_derivatives'];
        $newRow['external_related_material'] = $row['related_material'];
        $newRow['external_condition_note'] = $row['condition_note'];
        $newRow['external_created_on'] = $row['time_stamp'];
        $newRow['external_updated_on'] = $row['timestamp_-_last_change'];
        $newRow['external_user_name'] = $row['cataloger'];
        if ($row['media_type'] == 'Audio') {
            $newRow['external_media_duration'] = $row['media_duration'];
            $newRow['external_base'] = $row['base'];
            $newRow['external_disk_diameter'] = $row['disk_diameter'];
            $newRow['external_media_diameter'] = $row['media_diameter'];
            $newRow['external_tape_thickness'] = $row['tape_thickness'];
            $newRow['external_slides'] = $row['sides'];
            $newRow['external_track_type'] = $row['track_type'];
            $newRow['external_mono_stereo'] = $row['mono_or_stereo'];
            $newRow['external_noice_reduction'] = $row['noise_reduction'];
        }
        if ($row['media_type'] == 'Film') {
            $newRow['external_print_type'] = $row['print_type'];
            $newRow['external_footage'] = $row['footage'];
            $newRow['external_color'] = $row['color'];
            $newRow['external_reel_core'] = $row['reel_core'];
            $newRow['external_sound'] = $row['sound'];
            $newRow['external_frame_rate'] = $row['frame_rate'];
            $newRow['external_acid_detection'] = $row['acid_detection'];
            $newRow['external_shrinkage'] = $row['shrinkage'];
        }
        if ($row['media_type'] == 'Video') {
            $newRow['external_recording_speed'] = $row['recording_speed'];
            $newRow['external_cassette_size'] = $row['cassette_size'];
            $newRow['external_format_version'] = $row['format_version'];
            $newRow['external_media_duration'] = $row['media_duration'];
        }

        return $newRow;
    }

    /**
     * Get records from sphinx for merge export file
     *
     * @param type $user
     * @param type $sphinxInfo
     * @param type $sphinxCriteria
     * @param type $em
     *
     * @return type
     */
    public function fetchFromSphinxToMerge($user, $sphinxInfo, $sphinxCriteria, $em, $mergeToFile) {
        $phpExcelObject = null;
        $row = 2;
        $count = 0;
        $offset = 0;
        $sphinxObj = new SphinxSearch($em, $sphinxInfo);
        while ($count == 0) {
            $records = $sphinxObj->select($user, $offset, 1000, 'title', 'asc', $sphinxCriteria);
            $rec = $records[0];
            $totalFound = $records[1][1]['Value'];
            $phpExcelObject = $this->mergeRecords($rec, $mergeToFile, $phpExcelObject);
            $offset = $offset + 1000;
            $row++;
            if ($totalFound < 1000) {
                $count++;
            }
        }
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }

    public function makeExcelRowsByArray($activeSheet, $record, $mergRow, $row, $new_header = null) {
        if ($record) {
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record['project']);
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record['collection_name']);
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record['media_type']);
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $record['unique_id']);
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $record['alternate_id']);
            $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $record['location']);
            $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $record['format']);
            $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $record['title']);
            $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $record['description']);
            $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $record['commercial']);
            $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $record['content_duration']);
            $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $record['creation_date']);
            $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, $record['content_date']);
            $activeSheet->setCellValueExplicitByColumnAndRow(17, $row, $record['reel_diameter']);
            $activeSheet->setCellValueExplicitByColumnAndRow(36, $row, $record['genre_terms']);
            $activeSheet->setCellValueExplicitByColumnAndRow(37, $row, $record['contributor']);
            $activeSheet->setCellValueExplicitByColumnAndRow(38, $row, $record['generation']);
            $activeSheet->setCellValueExplicitByColumnAndRow(39, $row, $record['part']);
            $activeSheet->setCellValueExplicitByColumnAndRow(40, $row, $record['copyright_restrictions']);
            $activeSheet->setCellValueExplicitByColumnAndRow(41, $row, $record['duplicates_derivatives']);
            $activeSheet->setCellValueExplicitByColumnAndRow(42, $row, $record['related_material']);
            $activeSheet->setCellValueExplicitByColumnAndRow(43, $row, $record['condition_note']);
            $activeSheet->setCellValueExplicitByColumnAndRow(44, $row, $record['general_note']);
            $activeSheet->setCellValueExplicitByColumnAndRow(45, $row, ($record['created_on']) ? $record['created_on'] : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(46, $row, ($record['updated_on']) ? $record['updated_on'] : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(47, $row, $record['user_name']);

            if ($record['media_type'] == 'Audio') {
                $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $record['media_duration']);
                $activeSheet->setCellValueExplicitByColumnAndRow(14, $row, $record['base']);
                $activeSheet->setCellValueExplicitByColumnAndRow(16, $row, $record['disk_diameter']);
                $activeSheet->setCellValueExplicitByColumnAndRow(18, $row, $record['media_diameter']);
                $activeSheet->setCellValueExplicitByColumnAndRow(22, $row, $record['tape_thickness']);
                $activeSheet->setCellValueExplicitByColumnAndRow(23, $row, $record['slides']);
                $activeSheet->setCellValueExplicitByColumnAndRow(24, $row, $record['track_type']);
                $activeSheet->setCellValueExplicitByColumnAndRow(25, $row, $record['mono_stereo']);
                $activeSheet->setCellValueExplicitByColumnAndRow(26, $row, $record['noice_reduction']);
                $activeSheet->setCellValueExplicitByColumnAndRow(20, $row, $record['recording_speed']);
            }
            if ($record['media_type'] == 'Film') {
                $activeSheet->setCellValueExplicitByColumnAndRow(14, $row, $record['base']);
                $activeSheet->setCellValueExplicitByColumnAndRow(15, $row, $record['print_type']);
                $activeSheet->setCellValueExplicitByColumnAndRow(19, $row, $record['footage']);
                $activeSheet->setCellValueExplicitByColumnAndRow(21, $row, $record['color']);
                $activeSheet->setCellValueExplicitByColumnAndRow(30, $row, $record['reel_core']);
                $activeSheet->setCellValueExplicitByColumnAndRow(31, $row, $record['sound']);
                $activeSheet->setCellValueExplicitByColumnAndRow(32, $row, $record['edge_code_year']);
                $activeSheet->setCellValueExplicitByColumnAndRow(33, $row, $record['frame_rate']);
                $activeSheet->setCellValueExplicitByColumnAndRow(34, $row, $record['acid_detection']);
                $activeSheet->setCellValueExplicitByColumnAndRow(35, $row, $record['shrinkage']);
                $activeSheet->setCellValueExplicitByColumnAndRow(18, $row, $record['media_diameter']);
            }
            if ($record['media_type'] == 'Video') {
                $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $record['media_duration']);
                $activeSheet->setCellValueExplicitByColumnAndRow(20, $row, $record['recording_speed']);
                $activeSheet->setCellValueExplicitByColumnAndRow(27, $row, $record['cassette_size']);
                $activeSheet->setCellValueExplicitByColumnAndRow(28, $row, $record['format_version']);
                $activeSheet->setCellValueExplicitByColumnAndRow(29, $row, $record['recording_standard']);
            }
        }
        if ($mergRow) {
            $this->mergeRow($activeSheet, $mergRow, $row, $new_header);
        }
    }

    public function appendCellValuesByArray($record, $row) {
        $newRow = null;
        $newRow['project'] = $row['project_name'] ? $record['project'] . ' ' . $row['project_name'] : $record['project'];
        $newRow['collection_name'] = $row['collection_name'] ? $record['collection_name'] . ' ' . $row['collection_name'] : $record['collection_name'];
        $newRow['media_type'] = $row['media_type'] ? $record['media_type'] . ' ' . $row['media_type'] : $record['media_type'];
        $newRow['unique_id'] = $record['unique_id'];
        $newRow['location'] = $row['location'] ? $record['location'] . ' ' . $row['location'] : $record['location'];
        $newRow['format'] = $row['format'] ? $record['format'] . ' ' . $row['format'] : $record['format'];
        $newRow['title'] = $row['title'] ? $record['title'] . '' . $row['title'] : $record['title'];
        $newRow['description'] = $row['description'] ? $record['description'] . '' . $row['description'] : $record['description'];
        $newRow['commercial'] = $row['commercial_or_unique'] ? $record['commercial'] . ' ' . $row['commercial_or_unique'] : $record['commercial'];
        $newRow['content_duration'] = $row['content_duration'] ? $record['content_duration'] . ' ' . $row['content_duration'] : $record['content_duration'];
        $newRow['creation_date'] = $row['creation_date'] ? $record['creation_date'] . ' ' . $row['creation_date'] : $record['creation_date'];
        $newRow['content_date'] = $row['content_date'] ? $record['content_date'] . ' ' . $row['content_date'] : $record['content_date'];
        $newRow['reel_diameter'] = $row['reel_diameter'] ? $record['reel_diameter'] . ' ' . $row['reel_diameter'] : $record['reel_diameter'];
        $newRow['genre_terms'] = $row['genre_terms'] ? $record['genre_terms'] . ' ' . $row['genre_terms'] : $record['genre_terms'];
        $newRow['contributor'] = $row['contributor'] ? $record['contributor'] . ' ' . $row['contributor'] : $record['contributor'];
        $newRow['generation'] = $row['generation'] ? $record['generation'] . ' ' . $row['generation'] : $record['generation'];
        $newRow['part'] = $row['part'] ? $record['part'] . ' ' . $row['part'] : $record['part'];
        $newRow['copyright_restrictions'] = $row['copyright_/_restrictions'] ? $record['copyright_restrictions'] . ' ' . $row['copyright_/_restrictions'] : $record['copyright_restrictions'];
        $newRow['duplicates_derivatives'] = $row['duplicates_/_derivatives'] ? $record['duplicates_derivatives'] . ' ' . $row['genre_terms'] : $record['duplicates_derivatives'];
        $newRow['related_material'] = $row['related_material'] ? $record['related_material'] . ' ' . $row['duplicates_/_derivatives'] : $record['related_material'];
        $newRow['condition_note'] = $row['condition_note'] ? $record['condition_note'] . ' ' . $row['condition_note'] : $record['condition_note'];
        $newRow['created_on'] = ($row['time_stamp']) ? $record['created_on'] . ' ' . $row['time_stamp'] : $record['created_on'];
        $newRow['updated_on'] = $row['timestamp_-_last_change'] ? $record['updated_on'] . ' ' . $row['timestamp_-_last_change'] : $record['updated_on'];
        $newRow['user_name'] = $row['cataloger'] ? $record['user_name'] . ' ' . $row['cataloger'] : $record['user_name'];

        if ($row['media_type'] == 'Audio') {
            $newRow['media_duration'] = $row['media_duration'] ? $record['media_duration'] . ' ' . $row['media_duration'] : $record['media_duration'];
            $newRow['base'] = $row['base'] ? $record['base'] . ' ' . $row['base'] : $record['base'];
            $newRow['disk_diameter'] = $row['disk_diameter'] ? $record['disk_diameter'] . ' ' . $row['disk_diameter'] : $record['disk_diameter'];
            $newRow['media_diameter'] = $row['media_diameter'] ? $record['media_diameter'] . ' ' . $row['media_diameter'] : $record['media_diameter'];
            $newRow['tape_thickness'] = $row['tape_thickness'] ? $record['tape_thickness'] . ' ' . $row['tape_thickness'] : $record['tape_thickness'];
            $newRow['slides'] = $row['sides'] ? $record['slides'] . ' ' . $row['sides'] : $record['slides'];
            $newRow['track_type'] = $row['track_type'] ? $record['track_type'] . ' ' . $row['track_type'] : $record['track_type'];
            $newRow['mono_stereo'] = $row['mono_or_stereo'] ? $record['mono_stereo'] . ' ' . $row['mono_or_stereo'] : $record['mono_stereo'];
            $newRow['noice_reduction'] = $row['noise_reduction'] ? $record['noice_reduction'] . ' ' . $row['noise_reduction'] : $record['noice_reduction'];
        }
        if ($row['media_type'] == 'Film') {
            $newRow['print_type'] = $row['print_type'] ? $record['print_type'] . ' ' . $row['print_type'] : $record['print_type'];
            $newRow['footage'] = $row['footage'] ? $record['footage'] . ' ' . $row['footage'] : $record['footage'];
            $newRow['color'] = $row['color'] ? $record['color'] . ' ' . $row['color'] : $record['color'];
            $newRow['reel_core'] = $row['reel_core'] ? $record['reel_core'] . ' ' . $row['reel_core'] : $record['reel_core'];
            $newRow['sound'] = $row['sound'] ? $record['sound'] . ' ' . $row['sound'] : $record['sound'];
            $newRow['frame_rate'] = $row['frame_rate'] ? $record['frame_rate'] . ' ' . $row['frame_rate'] : $record['frame_rate'];
            $newRow['acid_detection'] = $row['acid_detection'] ? $record['acid_detection'] . ' ' . $row['acid_detection'] : $record['acid_detection'];
            $newRow['shrinkage'] = $row['shrinkage'] ? $record['shrinkage'] . ' ' . $row['shrinkage'] : $record['shrinkage'];
        }
        if ($row['media_type'] == 'Video') {
            $newRow['recording_speed'] = $row['recording_speed'] ? $record['recording_speed'] . ' ' . $row['recording_speed'] : $record['recording_speed'];
            $newRow['cassette_size'] = $row['cassette_size'] ? $record['cassette_size'] . ' ' . $row['cassette_size'] : $record['cassette_size'];
            $newRow['format_version'] = $row['format_version'] ? $record['format_version'] . ' ' . $row['format_version'] : $record['format_version'];
            $newRow['media_duration'] = $row['media_duration'] ? $record['media_duration'] . ' ' . $row['print_type'] : $record['media_duration'];
        }

        return $newRow;
    }

    public function generatePrioritizationReport($records) {
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
                ->setTitle("AVCC - Report")
                ->setSubject("Prioritization Report")
                ->setDescription("Prioritization Report");
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('Prioritization Report');
        $row = 1;
// Prepare header row for report
        $this->preparePrioritizationHeader($activeSheet, $row);
        $row++;
        $this->preparePrioritizationRecords($activeSheet, $row, $records);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }

    /**
     * Create the Header for report.
     *
     * @param  PHPExcel_Worksheet $activeSheet
     * @param  Integer            $row
     * @return boolean
     */
    private function preparePrioritizationHeader($activeSheet, $row) {
        $columns = new ExportFields();
        $this->columns = $columns->getPrioritizationColumns();
        foreach ($this->columns as $column => $columnName) {
            $activeSheet->setCellValueExplicitByColumnAndRow($column, $row, str_replace('_', ' ', $columnName));
            $activeSheet->getColumnDimensionByColumn($column)->setWidth(20);
            $activeSheet->getStyleByColumnAndRow($column)->getFont()->setBold(true);
        }

        return TRUE;
    }

    /**
     * Prepare rows for records.
     *
     * @param  PHPExcel_Worksheet $activeSheet
     * @param  Integer            $row
     * @return boolean
     */
    private function preparePrioritizationRecords($activeSheet, $row, $records) {
        foreach ($records as $record) {
            $score = 0;
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record->getProject());
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record->getCollectionName());
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record->getTitle());
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $record->getUniqueId());
            $score = $score + (float) (($record->getMediaType()) ? $record->getMediaType()->getScore() : 0);
            $score = $score + (float) (($record->getFormat()) ? $record->getFormat()->getScore() : 0);
            $score = $score + (float) (($record->getCommercial()) ? $record->getCommercial()->getScore() : 0);
            $score = $score + (float) (($record->getReelDiameters()) ? $record->getReelDiameters()->getScore() : 0);

            if ($record->getAudioRecord()) {
                //    $score = $score + ($record->getAudioRecord()->getMediaDuration()) ? $record->getAudioRecord()->getMediaDuration()->getscore() : 0;
                $score = $score + (float) (($record->getAudioRecord()->getBases()) ? $record->getAudioRecord()->getBases()->getScore() : 0);
                $score = $score + (float) (($record->getAudioRecord()->getDiskDiameters()) ? $record->getAudioRecord()->getDiskDiameters()->getScore() : 0);
                $score = $score + (float) (($record->getAudioRecord()->getMediaDiameters()) ? $record->getAudioRecord()->getMediaDiameters()->getScore() : 0);
                $score = $score + (float) (($record->getAudioRecord()->getTapeThickness()) ? $record->getAudioRecord()->getTapeThickness()->getScore() : 0);
                $score = $score + (float) (($record->getAudioRecord()->getSlides()) ? $record->getAudioRecord()->getSlides()->getScore() : 0);
                $score = $score + (float) (($record->getAudioRecord()->getTrackTypes()) ? $record->getAudioRecord()->getTrackTypes()->getScore() : 0);
                $score = $score + (float) (($record->getAudioRecord()->getMonoStereo()) ? $record->getAudioRecord()->getMonoStereo()->getScore() : 0);
                $score = $score + (float) (($record->getAudioRecord()->getNoiceReduction()) ? $record->getAudioRecord()->getNoiceReduction()->getScore() : 0);
            }
            if ($record->getFilmRecord()) {
                $score = $score + (float) (($record->getFilmRecord()->getPrintType()) ? $record->getFilmRecord()->getPrintType()->getScore() : 0);
                //  $score = $score + ($record->getFilmRecord()->getFootage()) ? $record->getFilmRecord()->getscore() : 0;
                $score = $score + (float) (($record->getFilmRecord()->getColors()) ? $record->getFilmRecord()->getColors()->getScore() : 0);
                $score = $score + (float) (($record->getFilmRecord()->getReelCore()) ? $record->getFilmRecord()->getReelCore()->getScore() : 0);
                $score = $score + (float) (($record->getFilmRecord()->getSound()) ? $record->getFilmRecord()->getSound()->getScore() : 0);
                $score = $score + (float) (($record->getFilmRecord()->getFrameRate()) ? $record->getFilmRecord()->getFrameRate()->getScore() : 0);
                $score = $score + (float) (($record->getFilmRecord()->getAcidDetectionStrip()) ? $record->getFilmRecord()->getAcidDetectionStrip()->getScore() : 0);
                //  $score = $score + ($record->getFilmRecord()->getShrinkage()) ? $record->getFilmRecord()->getscore() : 0;
            }
            if ($record->getVideoRecord()) {
                $score = $score + (float) (($record->getVideoRecord()->getRecordingSpeed()) ? $record->getVideoRecord()->getRecordingSpeed()->getScore() : 0);
                $score = $score + (float) (($record->getVideoRecord()->getCassetteSize()) ? $record->getVideoRecord()->getCassetteSize()->getScore() : 0);
                $score = $score + (float) (($record->getVideoRecord()->getFormatVersion()) ? $record->getVideoRecord()->getFormatVersion()->getScore() : 0);
                $score = $score + (float) (($record->getVideoRecord()->getRecordingStandard()) ? $record->getVideoRecord()->getRecordingStandard()->getScore() : 0);
            }
            $scale_score = ($score / 100) * 5;
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $scale_score);
            $row++;
        }

        return true;
    }

    protected function mergeRow($activeSheet, $mergRow, $row, $header) {
        $counter = 48;
        $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $mergRow['unique_id']);
        if (!empty($header)) {
            foreach ($header as $key => $value) {
                if (isset($mergRow[strtolower($value)])) {
                    $activeSheet->setCellValueExplicitByColumnAndRow($counter, $row, ($mergRow[strtolower($value)]) ? $mergRow[strtolower($value)] : '');
                }
                $counter = $counter + 1;
            }
        }
    }

    public function initMergeReport($merge_header = null) {
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
                ->setTitle("AVCC - Report")
                ->setSubject("Report for all formats")
                ->setDescription("Report for all formats");
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('All Formats');
        $row = 1;
// Prepare header row for report
        $this->prepareHeaderMerge($activeSheet, $row, $merge_header);

        return $phpExcelObject;
    }

    /**
     * Create the Header for report.
     *
     * @param  PHPExcel_Worksheet $activeSheet
     * @param  Integer            $row
     * @return boolean
     */
    private function prepareHeaderMerge($activeSheet, $row, $merge_header) {
        $columns = new ExportFields();
        $this->columns = array_merge($columns->getExportColumns(), $merge_header);
        foreach ($this->columns as $column => $columnName) {
            $activeSheet->setCellValueExplicitByColumnAndRow($column, $row, str_replace('_', ' ', $columnName));
            $activeSheet->getColumnDimensionByColumn($column)->setWidth(20);
            $activeSheet->getStyleByColumnAndRow($column)->getFont()->setBold(true);
        }

        return TRUE;
    }

    private function checkHeader($cell) {
        $columns = new ExportFields();
        $export = array_map('strtolower', $columns->getExportColumns());
        if (in_array(strtolower($cell), $export)) {
            return true;
        } else {
            return false;
        }
    }

    public function generateFileSizeAssetsReport($records) {
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
                ->setTitle("AVCC - Report")
                ->setSubject("File Size Calculator for Digitized Assets")
                ->setDescription("File Size Calculator for Digitized Assets");
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('File Size Calculator');
        $row = 2;

        $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, "File Size Calculator for Digitized Assets");
        $activeSheet->getColumnDimensionByColumn(1)->setWidth(20);
        $activeSheet->getStyleByColumnAndRow(1, $row)->getFont()->setBold(true);
        $row++;
        $exportFields = new ExportFields();
        $columns = $exportFields->getFileSizeCalculatorColumns();
        if (isset($records['Audio'])) {
            $this->prepareHeaderFileSizeCalculator($activeSheet, $row, $columns['audio']);
            $row++;
            $row = $this->prepareFileSizeCalculatorAudioRecords($activeSheet, $row, $records['Audio']);
        }
        $row = $row + 5;
        if (isset($records['Video'])) {
            $this->prepareHeaderFileSizeCalculator($activeSheet, $row, $columns['video']);
            $row++;
            $row = $this->prepareFileSizeCalculatorVideoRecords($activeSheet, $row, $records['Video']);
        }
        $row = $row + 5;
        if (isset($records['Film'])) {
            $this->prepareHeaderFileSizeCalculator($activeSheet, $row, $columns['film']);
            $row++;
            $row = $this->prepareFileSizeCalculatorFilmRecords($activeSheet, $row, $records['Film']);
        }
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }

    /**
     * Create the Header for report.
     *
     * @param  PHPExcel_Worksheet $activeSheet
     * @param  Integer            $row
     * @return boolean
     */
    private function prepareHeaderFileSizeCalculator($activeSheet, $row, $columns) {
        foreach ($columns as $column => $columnName) {
            $activeSheet->setCellValueExplicitByColumnAndRow($column, $row, $columnName);
            $activeSheet->getColumnDimensionByColumn($column)->setWidth(20);
            $activeSheet->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        }

        return TRUE;
    }

    private function prepareFileSizeCalculatorAudioRecords($activeSheet, $row, $records) {
        $i = 1;
        $totalUncompress1 = 0.00;
        $totalUncompress2 = 0.00;
        $totalUncompress3 = 0.00;
        $totalUncompress4 = 0.00;
        $totalUncompress5 = 0.00;
        $totalUncompress6 = 0.00;
        $totalUncompress7 = 0.00;
        $totalUncompress8 = 0.00;
        $totalKbps = 0.00;
        if ($records) {
            foreach ($records as $audio) {
                if ($i == 1)
                    $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "Audio");
                $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $audio['format']);
                $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $audio['total']);
                $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $audio['sum_content_duration']);
                $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, number_format($audio['sum_content_duration'] / $audio['total'], 2));
                $uncompress1 = $this->calculateFileSize($audio['sum_content_duration'], 34.56);
                $totalUncompress1 += $uncompress1;
                $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $uncompress1);
                $uncompress2 = $this->calculateFileSize($audio['sum_content_duration'], 17.28);
                $totalUncompress2 += $uncompress2;
                $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $uncompress2);
                $uncompress3 = $this->calculateFileSize($audio['sum_content_duration'], 11.52);
                $totalUncompress3 += $uncompress3;
                $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $uncompress3);
                $uncompress4 = $this->calculateFileSize($audio['sum_content_duration'], 10.584);
                $totalUncompress4 += $uncompress4;
                $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $uncompress4);
                $uncompress5 = $this->calculateFileSize($audio['sum_content_duration'], 17.28);
                $totalUncompress5 += $uncompress5;
                $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $uncompress5);
                $uncompress6 = $this->calculateFileSize($audio['sum_content_duration'], 8.64);
                $totalUncompress6 += $uncompress6;
                $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $uncompress6);
                $uncompress7 = $this->calculateFileSize($audio['sum_content_duration'], 5.75);
                $totalUncompress7 += $uncompress7;
                $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $uncompress7);
                $uncompress8 = $this->calculateFileSize($audio['sum_content_duration'], 5.292);
                $totalUncompress8 += $uncompress8;
                $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $uncompress8);
                $kbps = $this->calculateFileSize($audio['sum_content_duration'], 1.92);
                $totalKbps += $kbps;
                $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, $kbps);
                $i++;
                $row++;
            }
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "Total File Space");
            $activeSheet->getStyleByColumnAndRow(0, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $this->correctDecimal($totalUncompress1));
            $activeSheet->getStyleByColumnAndRow(5, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $this->correctDecimal($totalUncompress2));
            $activeSheet->getStyleByColumnAndRow(6, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $this->correctDecimal($totalUncompress3));
            $activeSheet->getStyleByColumnAndRow(7, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $this->correctDecimal($totalUncompress4));
            $activeSheet->getStyleByColumnAndRow(8, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $this->correctDecimal($totalUncompress5));
            $activeSheet->getStyleByColumnAndRow(9, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $this->correctDecimal($totalUncompress6));
            $activeSheet->getStyleByColumnAndRow(10, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $this->correctDecimal($totalUncompress7));
            $activeSheet->getStyleByColumnAndRow(11, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $this->correctDecimal($totalUncompress8));
            $activeSheet->getStyleByColumnAndRow(12, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, $this->correctDecimal($totalKbps, 5));
            $activeSheet->getStyleByColumnAndRow(13, $row)->getFont()->setBold(true);
            $row++;
        }

        return $row;
    }

    private function calculateFileSize($totalDuration, $value) {
        $totalSize = ($totalDuration * $value) / 1024 / 1024;
        return $this->correctDecimal($totalSize);
        //   return number_format(($totalDuration * $value) / 1024 / 1024, 1);
    }

    private function correctDecimal($totalDuration) {
        $depth = 0;
        $totalSize = $totalDuration;
        $size = $totalSize;
        if ($totalSize < 1 && $totalSize > 0) {
            while ($totalSize < 10) {
                $totalSize = $totalSize * 10;
                $depth += 1;
            }
            $depth = $depth - 1;
        } else if (is_float($totalSize)) {
            $depth = 1;
        }

        return number_format($size, $depth);
        //   return number_format(($totalDuration * $value) / 1024 / 1024, 1);
    }

    private function prepareFileSizeCalculatorVideoRecords($activeSheet, $row, $records) {
        $i = 1;
        $totalVUncompress1 = 0.00;
        $totalVUncompress2 = 0.00;
        $totalLossless = 0.00;
        $totalFFV1 = 0.00;
        $totalMPEG2 = 0.00;
        $totalProRes = 0.00;
        $totalDV25 = 0.00;
        $totalMPEG45 = 0.00;
        $totalMPEG42 = 0.00;
        if ($records) {
            foreach ($records as $video) {
                if ($i == 1)
                    $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "Video");
                $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $video['format']);
                $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $video['total']);
                $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $video['sum_content_duration']);
                $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, number_format($video['sum_content_duration'] / $video['total'], 2));
                $VUncompress1 = $this->calculateFileSize($video['sum_content_duration'], 10240);
                $totalVUncompress1 += $VUncompress1;
                $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $VUncompress1);
                $VUncompress2 = $this->calculateFileSize($video['sum_content_duration'], 1800);
                $totalVUncompress2 += $VUncompress2;
                $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $VUncompress2);
                $Lossless = $this->calculateFileSize($video['sum_content_duration'], 900);
                $totalLossless += $Lossless;
                $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $Lossless);
                $FFV1 = $this->calculateFileSize($video['sum_content_duration'], 600);
                $totalFFV1 += $FFV1;
                $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $FFV1);
                $MPEG2 = $this->calculateFileSize($video['sum_content_duration'], 427);
                $totalMPEG2 += $MPEG2;
                $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $MPEG2);
                $ProRes = $this->calculateFileSize($video['sum_content_duration'], 306);
                $totalProRes += $ProRes;
                $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $ProRes);
                $DV25 = $this->calculateFileSize($video['sum_content_duration'], 240);
                $totalDV25 += $DV25;
                $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $DV25);
                $MPEG45 = $this->calculateFileSize($video['sum_content_duration'], 36);
                $totalMPEG45 += $MPEG45;
                $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $MPEG45);
                $MPEG42 = $this->calculateFileSize($video['sum_content_duration'], 17.1);
                $totalMPEG42 += $MPEG42;
                $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, $MPEG42);
                $i++;
                $row++;
            }
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "Total File Space");
            $activeSheet->getStyleByColumnAndRow(0, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $this->correctDecimal($totalVUncompress1));
            $activeSheet->getStyleByColumnAndRow(5, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $this->correctDecimal($totalVUncompress2));
            $activeSheet->getStyleByColumnAndRow(6, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $this->correctDecimal($totalLossless));
            $activeSheet->getStyleByColumnAndRow(7, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $this->correctDecimal($totalFFV1));
            $activeSheet->getStyleByColumnAndRow(8, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $this->correctDecimal($totalMPEG2));
            $activeSheet->getStyleByColumnAndRow(9, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $this->correctDecimal($totalProRes));
            $activeSheet->getStyleByColumnAndRow(10, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $this->correctDecimal($totalDV25));
            $activeSheet->getStyleByColumnAndRow(11, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $this->correctDecimal($totalMPEG45));
            $activeSheet->getStyleByColumnAndRow(12, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, $this->correctDecimal($totalMPEG42));
            $activeSheet->getStyleByColumnAndRow(13, $row)->getFont()->setBold(true);
            $row++;
        }

        return $row;
    }

    private function prepareFileSizeCalculatorFilmRecords($activeSheet, $row, $records) {
        $i = 1;
        $total4kUnCommpressed = 0.00;
        $total4kLossLess = 0.00;
        $total2kUnCommpressed = 0.00;
        $total2KLossless = 0.00;
        $totalAVCIntra100 = 0.00;
        $totalMPEG45 = 0.00;
        $totalMPEG42 = 0.00;
        if ($records) {
            foreach ($records as $film) {
                if ($i == 1)
                    $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "Film");
                $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $film['format']);
                $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $film['total']);
                $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $film['sum_content_duration']);
                $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, number_format($film['sum_content_duration'] / $film['total'], 1));

                $k4Uncompressed = $this->calculateFileSize($film['sum_content_duration'], 69905);
                $total4kUnCommpressed += $k4Uncompressed;
                $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $k4Uncompressed);

                $k4Lossless = $this->calculateFileSize($film['sum_content_duration'], 34952.5);
                $total4kLossLess += $k4Lossless;
                $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $k4Lossless);

                $k2Uncompressed = $this->calculateFileSize($film['sum_content_duration'], 17500);
                $total2kUnCommpressed += $k2Uncompressed;
                $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $k2Uncompressed);

                $k2Lossless = $this->calculateFileSize($film['sum_content_duration'], 8750);
                $total2KLossless += $k2Lossless;
                $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $k2Lossless);

                $AVCIntra100 = $this->calculateFileSize($film['sum_content_duration'], 943);
                $totalAVCIntra100 += $AVCIntra100;
                $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $AVCIntra100);

                $MPEG45 = $this->calculateFileSize($film['sum_content_duration'], 36);
                $totalMPEG45 += $MPEG45;
                $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $MPEG45);

                $MPEG42 = $this->calculateFileSize($film['sum_content_duration'], 17.1);
                $totalMPEG42 += $MPEG42;
                $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $MPEG42);

                $i++;
                $row++;
            }
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "Total File Space");
            $activeSheet->getStyleByColumnAndRow(0, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $this->correctDecimal($total4kUnCommpressed));
            $activeSheet->getStyleByColumnAndRow(5, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $this->correctDecimal($total4kLossLess));
            $activeSheet->getStyleByColumnAndRow(6, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $this->correctDecimal($total2kUnCommpressed));
            $activeSheet->getStyleByColumnAndRow(7, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $this->correctDecimal($total2KLossless));
            $activeSheet->getStyleByColumnAndRow(8, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $this->correctDecimal($totalAVCIntra100));
            $activeSheet->getStyleByColumnAndRow(9, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $this->correctDecimal($totalMPEG45));
            $activeSheet->getStyleByColumnAndRow(10, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $this->correctDecimal($totalMPEG42));
            $activeSheet->getStyleByColumnAndRow(11, $row)->getFont()->setBold(true);
            $row++;
        }

        return $row;
    }

    public function generateLinearFootReport($records) {
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
                ->setTitle("AVCC - Report")
                ->setSubject("Linear Foot Calculator")
                ->setDescription("Linear Foot Calculator");
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('LinearFootCalculator');
        $row = 2;

        $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, "Linear Foot Calculator");
        $activeSheet->getColumnDimensionByColumn(1)->setWidth(20);
        $activeSheet->getStyleByColumnAndRow(1, $row)->getFont()->setBold(true);
        $row++;
        $exportFields = new ExportFields();
        $columns = $exportFields->getLinearFootCalculatorColumns();
        if ($records) {
            $this->prepareHeaderLinearFootCalculator($activeSheet, $row, $columns);
            $row++;
            $row = $this->prepareLinearFootCalculatorRecords($activeSheet, $row, $records);
        }
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }

    /**
     * Create the Header for report.
     *
     * @param  PHPExcel_Worksheet $activeSheet
     * @param  Integer            $row
     * @return boolean
     */
    private function prepareHeaderLinearFootCalculator($activeSheet, $row, $columns) {
        foreach ($columns as $column => $columnName) {
            $activeSheet->setCellValueExplicitByColumnAndRow($column, $row, $columnName);
            $activeSheet->getColumnDimensionByColumn($column)->setWidth(20);
            $activeSheet->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        }

        return TRUE;
    }

    private function prepareLinearFootCalculatorRecords($activeSheet, $row, $records) {
        $totalLinearAudioCount = 0.00;
        $totalLinearVideoCount = 0.00;
        $totalLinearCount = 0.00;
        if ($records) {
            if ($records['audio']) {
                foreach ($records['audio'] as $audio) {
                    $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "Audio");
                    $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $audio['format']);
                    $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, round($audio['width'], 1));
                    $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $audio['total']);
                    $linearAudioCount = $this->calculateLinearFeet($audio['total'], $audio['width']);
                    $totalLinearAudioCount += $linearAudioCount;
                    $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $linearAudioCount);

                    $row++;
                }
                $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "");
                $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, "");
                $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, "");
                $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, "Total Linear Feet Audio:");
                $activeSheet->getStyleByColumnAndRow(3, $row)->getFont()->setBold(true);
                $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, number_format($totalLinearAudioCount, 5));
                $activeSheet->getStyleByColumnAndRow(4, $row)->getFont()->setBold(true);
                $row++;
            }
            if ($records['video']) {
                foreach ($records['video'] as $video) {
                    $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "Video");
                    $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $video['format']);
                    $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, round($video['width'], 1));
                    $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $video['total']);
                    $linearVideoCount = $this->calculateLinearFeet($video['total'], $video['width']);
                    $totalLinearVideoCount += $linearVideoCount;
                    $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $linearVideoCount);

                    $row++;
                }
                $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "");
                $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, "");
                $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, "");
                $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, "Total Linear Feet Video:");
                $activeSheet->getStyleByColumnAndRow(3, $row)->getFont()->setBold(true);
                $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, number_format($totalLinearVideoCount, 5));
                $activeSheet->getStyleByColumnAndRow(4, $row)->getFont()->setBold(true);
                $row++;
            }
            $totalLinearCount = $totalLinearAudioCount + $totalLinearVideoCount;
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, "");
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, "Grand Total:");
            $activeSheet->getStyleByColumnAndRow(3, $row)->getFont()->setBold(true);
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, number_format($totalLinearCount, 5));
            $activeSheet->getStyleByColumnAndRow(4, $row)->getFont()->setBold(true);
        }

        return $row;
    }

    private function calculateLinearFeet($totalCount, $width) {
        return number_format(($totalCount * $width) / 12, 1);
    }

}
