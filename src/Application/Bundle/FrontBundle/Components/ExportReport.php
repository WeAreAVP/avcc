<?php

namespace Application\Bundle\FrontBundle\Components;

use Symfony\Component\DependencyInjection\ContainerAware;
use Application\Bundle\FrontBundle\Helper\ExportFields;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use PHPExcel_Cell;

class ExportReport extends ContainerAware
{

    public $columns;
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function prepareManifestReport($activeSheet, $records)
    {
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
        $row ++;

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
            $row ++;
        }
    }

    public function generateReport($records)
    {
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
        $row ++;
        $this->prepareRecords($activeSheet, $row, $records);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        return $phpExcelObject;
    }

    public function outputReport($type, $phpExcelObject, $fileStartName = 'allFormat')
    {
        $format = ($type == 'csv') ? 'CSV' : 'Excel2007';
        $writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, $format);
        $filename = $fileStartName . '_' . time() . '.' . $type;
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
    public function saveReport($type, $phpExcelObject)
    {
        $format = ($type == 'csv') ? 'CSV' : 'Excel2007';
        $writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, $format);
        $filename = 'allFormat_' . time() . '.' . $type;
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
    private function prepareHeader($activeSheet, $row)
    {
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
    private function prepareRecords($activeSheet, $row, $records)
    {

        foreach ($records as $record) {
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record->getProject());
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record->getCollectionName());
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record->getMediaType());
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $record->getUniqueId());
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $record->getLocation());
            $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, ($record->getFormat()->getName()) ? $record->getFormat()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $record->getTitle());
            $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $record->getDescription());
            $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, ($record->getCommercial()) ? $record->getCommercial()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $record->getContentDuration());
            $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $record->getCreationDate());
            $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $record->getContentDate());
            $activeSheet->setCellValueExplicitByColumnAndRow(16, $row, ($record->getReelDiameters()) ? $record->getReelDiameters()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(34, $row, ($record->getGenreTerms()));
            $activeSheet->setCellValueExplicitByColumnAndRow(35, $row, ($record->getContributor()));
            $activeSheet->setCellValueExplicitByColumnAndRow(36, $row, $record->getGeneration());
            $activeSheet->setCellValueExplicitByColumnAndRow(37, $row, $record->getPart());
            $activeSheet->setCellValueExplicitByColumnAndRow(38, $row, $record->getCopyrightRestrictions());
            $activeSheet->setCellValueExplicitByColumnAndRow(39, $row, $record->getDuplicatesDerivatives());
            $activeSheet->setCellValueExplicitByColumnAndRow(40, $row, $record->getRelatedMaterial());
            $activeSheet->setCellValueExplicitByColumnAndRow(41, $row, $record->getConditionNote());
            $activeSheet->setCellValueExplicitByColumnAndRow(42, $row, $record->getCreatedOn()->format('Y-m-d H:i:s'));
            $activeSheet->setCellValueExplicitByColumnAndRow(43, $row, ($record->getUpdatedOn()) ? $record->getUpdatedOn()->format('Y-m-d H:i:s') : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(44, $row, $record->getUser()->getName());

            if ($record->getAudioRecord()) {
                $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, ($record->getAudioRecord()->getMediaDuration()) ? $record->getAudioRecord()->getMediaDuration() : "");
                $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, ($record->getAudioRecord()->getBases()) ? $record->getAudioRecord()->getBases()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(15, $row, ($record->getAudioRecord()->getDiskDiameters()) ? $record->getAudioRecord()->getDiskDiameters()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(17, $row, ($record->getAudioRecord()->getMediaDiameters()) ? $record->getAudioRecord()->getMediaDiameters()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(21, $row, ($record->getAudioRecord()->getTapeThickness()) ? $record->getAudioRecord()->getTapeThickness()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(22, $row, ($record->getAudioRecord()->getSlides()) ? $record->getAudioRecord()->getSlides()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(23, $row, ($record->getAudioRecord()->getTrackTypes()) ? $record->getAudioRecord()->getTrackTypes()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(24, $row, ($record->getAudioRecord()->getMonoStereo()) ? $record->getAudioRecord()->getMonoStereo()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(25, $row, ($record->getAudioRecord()->getNoiceReduction()) ? $record->getAudioRecord()->getNoiceReduction()->getName() : '');
            }
            if ($record->getFilmRecord()) {
                $activeSheet->setCellValueExplicitByColumnAndRow(14, $row, ($record->getFilmRecord()->getPrintType()) ? $record->getFilmRecord()->getPrintType()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(18, $row, ($record->getFilmRecord()->getFootage()) ? $record->getFilmRecord()->getFootage() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(20, $row, ($record->getFilmRecord()->getColors()) ? $record->getFilmRecord()->getColors()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(29, $row, ($record->getFilmRecord()->getReelCore()) ? $record->getFilmRecord()->getReelCore()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(30, $row, ($record->getFilmRecord()->getSound()) ? $record->getFilmRecord()->getSound()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(31, $row, ($record->getFilmRecord()->getFrameRate()) ? $record->getFilmRecord()->getFrameRate()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(32, $row, ($record->getFilmRecord()->getAcidDetectionStrip()) ? $record->getFilmRecord()->getAcidDetectionStrip()->getName() : "");
                $activeSheet->setCellValueExplicitByColumnAndRow(33, $row, ($record->getFilmRecord()->getShrinkage()) ? $record->getFilmRecord()->getShrinkage() : '');
            }
            if ($record->getVideoRecord()) {

                $activeSheet->setCellValueExplicitByColumnAndRow(19, $row, ($record->getVideoRecord()->getRecordingSpeed()) ? $record->getVideoRecord()->getRecordingSpeed()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(26, $row, ($record->getVideoRecord()->getCassetteSize()) ? $record->getVideoRecord()->getCassetteSize()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(27, $row, ($record->getVideoRecord()->getFormatVersion()) ? $record->getVideoRecord()->getFormatVersion()->getName() : '');
                $activeSheet->setCellValueExplicitByColumnAndRow(28, $row, ($record->getVideoRecord()->getRecordingStandard()) ? $record->getVideoRecord()->getRecordingStandard()->getName() : '');
            }$row ++;
        }

        return true;
    }

    public function initReport()
    {
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

    public function fetchFromSphinx($user, $sphinxInfo, $sphinxCriteria, $em)
    {
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
            $row++;
            if ($totalFound < 1000) {
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
    private function prepareRecordsFromSphinx($activeSheet, $row, $records)
    {
        foreach ($records as $record) {
            $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record['project']);
            $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record['collection_name']);
            $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record['media_type']);
            $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $record['unique_id']);
            $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $record['location']);
            $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $record['format']);
            $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $record['title']);
            $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $record['description']);
            $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $record['commercial']);
            $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $record['content_duration']);
            $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $record['creation_date']);
            $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $record['content_date']);
            $activeSheet->setCellValueExplicitByColumnAndRow(16, $row, $record['reel_diameter']);
            $activeSheet->setCellValueExplicitByColumnAndRow(34, $row, $record['genre_terms']);
            $activeSheet->setCellValueExplicitByColumnAndRow(35, $row, $record['contributor']);
            $activeSheet->setCellValueExplicitByColumnAndRow(36, $row, $record['generation']);
            $activeSheet->setCellValueExplicitByColumnAndRow(37, $row, $record['part']);
            $activeSheet->setCellValueExplicitByColumnAndRow(38, $row, $record['copyright_restrictions']);
            $activeSheet->setCellValueExplicitByColumnAndRow(39, $row, $record['duplicates_derivatives']);
            $activeSheet->setCellValueExplicitByColumnAndRow(40, $row, $record['related_material']);
            $activeSheet->setCellValueExplicitByColumnAndRow(41, $row, $record['condition_note']);
            $activeSheet->setCellValueExplicitByColumnAndRow(42, $row, ($record['created_on']) ? $record['created_on'] : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(43, $row, ($record['updated_on']) ? $record['updated_on'] : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(44, $row, $record['user_name']);

            if ($record['media_type'] == 'Audio') {
                $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $record['media_duration']);
                $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, $record['base']);
                $activeSheet->setCellValueExplicitByColumnAndRow(15, $row, $record['disk_diameter']);
                $activeSheet->setCellValueExplicitByColumnAndRow(17, $row, $record['media_diameter']);
                $activeSheet->setCellValueExplicitByColumnAndRow(21, $row, $record['tape_thickness']);
                $activeSheet->setCellValueExplicitByColumnAndRow(22, $row, $record['slides']);
                $activeSheet->setCellValueExplicitByColumnAndRow(23, $row, $record['track_type']);
                $activeSheet->setCellValueExplicitByColumnAndRow(24, $row, $record['mono_stereo']);
                $activeSheet->setCellValueExplicitByColumnAndRow(25, $row, $record['noice_reduction']);
            }
            if ($record['media_type'] == 'Film') {
                $activeSheet->setCellValueExplicitByColumnAndRow(14, $row, $record['print_type']);
                $activeSheet->setCellValueExplicitByColumnAndRow(18, $row, $record['footage']);
                $activeSheet->setCellValueExplicitByColumnAndRow(20, $row, $record['color']);
                $activeSheet->setCellValueExplicitByColumnAndRow(29, $row, $record['reel_core']);
                $activeSheet->setCellValueExplicitByColumnAndRow(30, $row, $record['sound']);
                $activeSheet->setCellValueExplicitByColumnAndRow(31, $row, $record['frame_rate']);
                $activeSheet->setCellValueExplicitByColumnAndRow(32, $row, $record['acid_detection']);
                $activeSheet->setCellValueExplicitByColumnAndRow(33, $row, $record['shrinkage']);
            }
            if ($record['media_type'] == 'Video') {
                $activeSheet->setCellValueExplicitByColumnAndRow(19, $row, $record['recording_speed']);
                $activeSheet->setCellValueExplicitByColumnAndRow(26, $row, $record['cassette_size']);
                $activeSheet->setCellValueExplicitByColumnAndRow(27, $row, $record['format_version']);
                $activeSheet->setCellValueExplicitByColumnAndRow(28, $row, $record['media_duration']);
            }
            $row ++;
        }
    }

    public function megerRecords($records, $mergeToFile)
    {
    $mergeFileCompletePath = $this->container->getParameter('webUrl') . 'merge/' . date('Y') . '/' . date('m') . '/' . $mergeToFile;
   
//        $mergeFileCompletePath = '/Applications/XAMPP/xamppfiles/htdocs/avcc/web/' . $mergeToFile;
        if (file_exists($mergeFileCompletePath)) {
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($mergeFileCompletePath);
            $newphpExcelObject = $this->initReport();
            $activeSheet = $newphpExcelObject->setActiveSheetIndex(0);
            
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                $worksheetTitle = $worksheet->getTitle();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $excelCell = new PHPExcel_Cell(null, null, $worksheet);
                $highestColumnIndex = $excelCell->columnIndexFromString($highestColumn);
                echo $highestRow;exit;
                if ($highestRow > 0) {
                    $rows = array();
                    $newRows = array();
                    $newrow = 2;
                    foreach ($records as $record) {                        
                        for ($row = 2; $row <= $highestRow; ++$row) {
                            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                $matched = false;
                                if ($record->getUniqueId() == $worksheet->getCellByColumnAndRow(3, $row)) {
                                    $matched = true;
                                    return $record->getUniqueId();
                                }
                                if ($matched) {
                                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                    $columnName = strtolower(str_replace(' ', '_', $worksheet->getCellByColumnAndRow($col, 1)));
                                    $rows[$row - 1][$columnName] = $cell->getValue();
                                    $newRows[$row - 1] = $this->appendCellValues($record, $rows[$row - 1]);
                                    $this->makeExcelRowsByArray($activeSheet, $newrow, $newRows);
                                } else {
                                    $this->makeExcelRows($activeSheet, $record, $newrow);
                                }
                                $newrow ++;
                            }
                        }
                    }

                    if ($records) {
                        return $newphpExcelObject;
                    }
                } else {
                    return "The file $mergeToFile is empty";
                }
            }
        } else {
            return "The file $mergeToFile does not exist";
        }
    }

    public function makeExcelRows($activeSheet, $record, $row)
    {
        $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record->getProject());
        $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record->getCollectionName());
        $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record->getMediaType());
        $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $record->getUniqueId());
        $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $record->getLocation());
        $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, ($record->getFormat()->getName()) ? $record->getFormat()->getName() : '');
        $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $record->getTitle());
        $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $record->getDescription());
        $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, ($record->getCommercial()) ? $record->getCommercial()->getName() : '');
        $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $record->getContentDuration());
        $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $record->getCreationDate());
        $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $record->getContentDate());
        $activeSheet->setCellValueExplicitByColumnAndRow(16, $row, ($record->getReelDiameters()) ? $record->getReelDiameters()->getName() : '');
        $activeSheet->setCellValueExplicitByColumnAndRow(34, $row, ($record->getGenreTerms()));
        $activeSheet->setCellValueExplicitByColumnAndRow(35, $row, ($record->getContributor()));
        $activeSheet->setCellValueExplicitByColumnAndRow(36, $row, $record->getGeneration());
        $activeSheet->setCellValueExplicitByColumnAndRow(37, $row, $record->getPart());
        $activeSheet->setCellValueExplicitByColumnAndRow(38, $row, $record->getCopyrightRestrictions());
        $activeSheet->setCellValueExplicitByColumnAndRow(39, $row, $record->getDuplicatesDerivatives());
        $activeSheet->setCellValueExplicitByColumnAndRow(40, $row, $record->getRelatedMaterial());
        $activeSheet->setCellValueExplicitByColumnAndRow(41, $row, $record->getConditionNote());
        $activeSheet->setCellValueExplicitByColumnAndRow(42, $row, $record->getCreatedOn()->format('Y-m-d H:i:s'));
        $activeSheet->setCellValueExplicitByColumnAndRow(43, $row, ($record->getUpdatedOn()) ? $record->getUpdatedOn()->format('Y-m-d H:i:s') : '');
        $activeSheet->setCellValueExplicitByColumnAndRow(44, $row, $record->getUser()->getName());

        if ($record->getAudioRecord()) {
            $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, ($record->getAudioRecord()->getMediaDuration()) ? $record->getAudioRecord()->getMediaDuration() : "");
            $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, ($record->getAudioRecord()->getBases()) ? $record->getAudioRecord()->getBases()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(15, $row, ($record->getAudioRecord()->getDiskDiameters()) ? $record->getAudioRecord()->getDiskDiameters()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(17, $row, ($record->getAudioRecord()->getMediaDiameters()) ? $record->getAudioRecord()->getMediaDiameters()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(21, $row, ($record->getAudioRecord()->getTapeThickness()) ? $record->getAudioRecord()->getTapeThickness()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(22, $row, ($record->getAudioRecord()->getSlides()) ? $record->getAudioRecord()->getSlides()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(23, $row, ($record->getAudioRecord()->getTrackTypes()) ? $record->getAudioRecord()->getTrackTypes()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(24, $row, ($record->getAudioRecord()->getMonoStereo()) ? $record->getAudioRecord()->getMonoStereo()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(25, $row, ($record->getAudioRecord()->getNoiceReduction()) ? $record->getAudioRecord()->getNoiceReduction()->getName() : '');
        }
        if ($record->getFilmRecord()) {
            $activeSheet->setCellValueExplicitByColumnAndRow(14, $row, ($record->getFilmRecord()->getPrintType()) ? $record->getFilmRecord()->getPrintType()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(18, $row, ($record->getFilmRecord()->getFootage()) ? $record->getFilmRecord()->getFootage() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(20, $row, ($record->getFilmRecord()->getColors()) ? $record->getFilmRecord()->getColors()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(29, $row, ($record->getFilmRecord()->getReelCore()) ? $record->getFilmRecord()->getReelCore()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(30, $row, ($record->getFilmRecord()->getSound()) ? $record->getFilmRecord()->getSound()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(31, $row, ($record->getFilmRecord()->getFrameRate()) ? $record->getFilmRecord()->getFrameRate()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(32, $row, ($record->getFilmRecord()->getAcidDetectionStrip()) ? $record->getFilmRecord()->getAcidDetectionStrip()->getName() : "");
            $activeSheet->setCellValueExplicitByColumnAndRow(33, $row, ($record->getFilmRecord()->getShrinkage()) ? $record->getFilmRecord()->getShrinkage() : '');
        }
        if ($record->getVideoRecord()) {
            $activeSheet->setCellValueExplicitByColumnAndRow(19, $row, ($record->getVideoRecord()->getRecordingSpeed()) ? $record->getVideoRecord()->getRecordingSpeed()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(26, $row, ($record->getVideoRecord()->getCassetteSize()) ? $record->getVideoRecord()->getCassetteSize()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(27, $row, ($record->getVideoRecord()->getFormatVersion()) ? $record->getVideoRecord()->getFormatVersion()->getName() : '');
            $activeSheet->setCellValueExplicitByColumnAndRow(28, $row, ($record->getVideoRecord()->getRecordingStandard()) ? $record->getVideoRecord()->getRecordingStandard()->getName() : '');
        }
        
    }

    public function appendCellValues($record, $row)
    {
        $newRow = null;
        $newRow['project'] = $row['project'] ? $record->getProject() . ' ' . $row['project'] : $record->getProject();
        $newRow['collection_name'] = $row['collection_name'] ? $record->getCollectionName() . ' ' . $row['collection_name'] : $record->getCollectionName();
        $newRow['media_type'] = $row['media_type'] ? $record->getMediaType() . ' ' . $row['media_type'] : $record->getMediaType();
        $newRow['unique_id'] = $row['unique_id'] ? $record->getUniqueId() . ' ' . $row['unique_id'] : $record->getUniqueId();
        $newRow['location'] = $row['location'] ? $record->getLocation() . ' ' . $row['location'] : $record->getLocation();
        $newRow['format'] = $row['format'] ? ($record->getFormat()->getName() ? $record->getFormat()->getName() . ' ' . $row['format'] : $row['format']) : ($record->getFormat()->getName()) ? $record->getFormat()->getName() : '';
        $newRow['title'] = $row['title'] ? $record->getTitle() . '' . $row['title'] : $record->getTitle();
        $newRow['description'] = $row['description'] ? $record->getDescription() . '' . $row['description'] : $record->getDescription();
        $newRow['commercial'] = $row['commercial'] ? ($record->getCommercial() ? $record->getCommercial()->getName() . ' ' . $row['commercial'] : '') : ($record->getCommercial() ? $record->getCommercial()->getName() : '');
        $newRow['content_duration'] = $row['content_duration'] ? $record->getContentDuration() . ' ' . $row['content_duration'] : $record->getContentDuration();
        $newRow['creation_date'] = $row['creation_date'] ? $record->getCreationDate() . ' ' . $row['creation_date'] : $record->getCreationDate();
        $newRow['content_date'] = $row['content_date'] ? $record->getContentDate() . ' ' . $row['content_date'] : $record->getContentDate();
        $newRow['reel_diameter'] = $row['reel_diameter'] ? ($record->getReelDiameters() ? $record->getReelDiameters()->getName() . ' ' . $row['reel_diameter'] : '') : ($record->getReelDiameters() ? $record->getReelDiameters()->getName() : '');
        $newRow['genre_terms'] = $row['genre_terms'] ? $record->getGenreTerms() . ' ' . $row['genre_terms'] : $record->getGenreTerms();
        $newRow['contributor'] = $row['contributor'] ? $record->getContributor() . ' ' . $row['genre_terms'] : $record->getContributor();
        $newRow['generation'] = $row['generation'] ? $record->getGeneration() . ' ' . $row['genre_terms'] : $record->getGeneration();
        $newRow['part'] = $row['part'] ? $record->getPart() . ' ' . $row['genre_terms'] : $record->getPart();
        $newRow['copyright_restrictions'] = $row['copyright_restrictions'] ? $record->getCopyrightRestrictions() . ' ' . $row['genre_terms'] : $record->getCopyrightRestrictions();
        $newRow['duplicates_derivatives'] = $row['duplicates_derivatives'] ? $record->getDuplicatesDerivatives() . ' ' . $row['genre_terms'] : $record->getDuplicatesDerivatives();
        $newRow['related_material'] = $row['related_material'] ? $record->getRelatedMaterial() . ' ' . $row['genre_terms'] : $record->getRelatedMaterial();
        $newRow['condition_note'] = $row['condition_note'] ? $record->getConditionNote() . ' ' . $row['genre_terms'] : $record->getConditionNote();
        $newRow['created_on'] = ($row['created_on']) ? $record->getCreatedOn()->format('Y-m-d H:i:s') . ' ' . $row['created_on'] : $record->getCreatedOn()->format('Y-m-d H:i:s');
        $newRow['updated_on'] = $row['updated_on'] ? ($record->getUpdatedOn() ? $record->getUpdatedOn()->format('Y-m-d H:i:s') . ' ' . $row['updated_on'] : '') : ($record->getUpdatedOn() ? $record->getUpdatedOn()->format('Y-m-d H:i:s') : '');
        $newRow['user_name'] = $row['user_name'] ? $record->getUser()->getName() . ' ' . $row['user_name'] : $record->getUser()->getName();

        if ($row['media_type'] == 'Audio') {
            $newRow['media_duration'] = $row['media_duration'] ? ($record->getAudioRecord()->getMediaDuration() ? $record->getAudioRecord()->getMediaDuration() . ' ' . $row['media_duration'] : "") : ($record->getAudioRecord()->getMediaDuration() ? $record->getAudioRecord()->getMediaDuration() : "");
            $newRow['base'] = $row['base'] ? ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() . ' ' . $row['base'] : "") : ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "");
            $newRow['disk_diameter'] = $row['disk_diameter'] ? ($record->getAudioRecord()->getDiskDiameters() ? $record->getAudioRecord()->getDiskDiameters()->getName() . ' ' . $row['disk_diameter'] : "") : ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "");
            $newRow['media_diameter'] = $row['media_diameter'] ? ($record->getAudioRecord()->getMediaDiameters() ? $record->getAudioRecord()->getMediaDiameters()->getName() . ' ' . $row['media_diameter'] : "") : ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "");
            $newRow['tape_thickness'] = $row['tape_thickness'] ? ($record->getAudioRecord()->getTapeThickness() ? $record->getAudioRecord()->getTapeThickness()->getName() . ' ' . $row['tape_thickness'] : "") : ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "");
            $newRow['slides'] = $row['slides'] ? ($record->getAudioRecord()->getSlides() ? $record->getAudioRecord()->getSlides()->getName() . ' ' . $row['slides'] : "") : ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "");
            $newRow['track_type'] = $row['track_type'] ? ($record->getAudioRecord()->getTrackTypes() ? $record->getAudioRecord()->getTrackTypes()->getName() . ' ' . $row['track_type'] : "") : ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "");
            $newRow['mono_stereo'] = $row['mono_stereo'] ? ($record->getAudioRecord()->getMonoStereo() ? $record->getAudioRecord()->getMonoStereo()->getName() . ' ' . $row['mono_stereo'] : "") : ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "");
            $newRow['noice_reduction'] = $row['noice_reduction'] ? ($record->getAudioRecord()->getNoiceReduction() ? $record->getAudioRecord()->getNoiceReduction()->getName() . ' ' . $row['noice_reduction'] : "") : ($record->getAudioRecord()->getBases() ? $record->getAudioRecord()->getBases()->getName() : "");
        }
        if ($row['media_type'] == 'Film') {
            $newRow['print_type'] = $row['print_type'] ? ($record->getFilmRecord()->getPrintType() ? $record->getFilmRecord()->getPrintType()->getName() . ' ' . $row['print_type'] : "") : ($record->getFilmRecord()->getPrintType() ? $record->getFilmRecord()->getPrintType()->getName() : "");
            $newRow['footage'] = $row['footage'] ? ($record->getFilmRecord()->getFootage() ? $record->getFilmRecord()->getFootage()->getName() . ' ' . $row['footage'] : "") : ($record->getFilmRecord()->getFootage() ? $record->getFilmRecord()->getFootage()->getName() : "");
            $newRow['color'] = $row['color'] ? ($record->getFilmRecord()->getColors() ? $record->getFilmRecord()->getColors()->getName() . ' ' . $row['color'] : "") : ($record->getFilmRecord()->getColors() ? $record->getFilmRecord()->getColors()->getName() : "");
            $newRow['reel_core'] = $row['reel_core'] ? ($record->getFilmRecord()->getReelCore() ? $record->getFilmRecord()->getReelCore()->getName() . ' ' . $row['reel_core'] : "") : ($record->getFilmRecord()->getReelCore() ? $record->getFilmRecord()->getReelCore()->getName() : "");
            $newRow['sound'] = $row['sound'] ? ($record->getFilmRecord()->getSound() ? $record->getFilmRecord()->getSound()->getName() . ' ' . $row['sound'] : "") : ($record->getFilmRecord()->getSound() ? $record->getFilmRecord()->getSound()->getName() : "");
            $newRow['frame_rate'] = $row['frame_rate'] ? ($record->getFilmRecord()->getFrameRate() ? $record->getFilmRecord()->getFrameRate()->getName() . ' ' . $row['frame_rate'] : "") : ($record->getFilmRecord()->getFrameRate() ? $record->getFilmRecord()->getFrameRate()->getName() : "");
            $newRow['acid_detection'] = $row['acid_detection'] ? ($record->getFilmRecord()->getAcidDetectionStrip() ? $record->getFilmRecord()->getAcidDetectionStrip()->getName() . ' ' . $row['acid_detection'] : "") : ($record->getFilmRecord()->getAcidDetectionStrip() ? $record->getFilmRecord()->getAcidDetectionStrip()->getName() : "");
            $newRow['shrinkage'] = $row['shrinkage'] ? ($record->getFilmRecord()->getShrinkage() ? $record->getFilmRecord()->getShrinkage()->getName() . ' ' . $row['shrinkage'] : "") : ($record->getFilmRecord()->getShrinkage() ? $record->getFilmRecord()->getShrinkage()->getName() : "");
        }
        if ($row['media_type'] == 'Video') {
            $newRow['recording_speed'] = $row['recording_speed'] ? ($record->getVideoRecord()->getRecordingSpeed() ? $record->getVideoRecord()->getRecordingSpeed()->getName() . ' ' . $row['print_type'] : "") : ($record->getVideoRecord()->getRecordingSpeed() ? $record->getVideoRecord()->getRecordingSpeed()->getName() : "");
            $newRow['cassette_size'] = $row['cassette_size'] ? ($record->getVideoRecord()->getCassetteSize() ? $record->getVideoRecord()->getCassetteSize()->getName() . ' ' . $row['print_type'] : "") : ($record->getVideoRecord()->getCassetteSize() ? $record->getVideoRecord()->getCassetteSize()->getName() : "");
            $newRow['format_version'] = $row['format_version'] ? ($record->getVideoRecord()->getFormatVersion() ? $record->getVideoRecord()->getFormatVersion()->getName() . ' ' . $row['print_type'] : "") : ($record->getVideoRecord()->getFormatVersion() ? $record->getVideoRecord()->getFormatVersion()->getName() : "");
            $newRow['media_duration'] = $row['media_duration'] ? ($record->getVideoRecord()->getRecordingStandard() ? $record->getVideoRecord()->getRecordingStandard()->getName() . ' ' . $row['print_type'] : "") : ($record->getVideoRecord()->getRecordingStandard() ? $record->getVideoRecord()->getRecordingStandard()->getName() : "");
        }

        return $newRow;
    }

    public function makeExcelRowsByArray($activeSheet, $record, $row)
    {
        $activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record['project']);
        $activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record['collection_name']);
        $activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record['media_type']);
        $activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $record['unique_id']);
        $activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $record['location']);
        $activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $record['format']);
        $activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $record['title']);
        $activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $record['description']);
        $activeSheet->setCellValueExplicitByColumnAndRow(8, $row, $record['commercial']);
        $activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $record['content_duration']);
        $activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $record['creation_date']);
        $activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $record['content_date']);
        $activeSheet->setCellValueExplicitByColumnAndRow(16, $row, $record['reel_diameter']);
        $activeSheet->setCellValueExplicitByColumnAndRow(34, $row, $record['genre_terms']);
        $activeSheet->setCellValueExplicitByColumnAndRow(35, $row, $record['contributor']);
        $activeSheet->setCellValueExplicitByColumnAndRow(36, $row, $record['generation']);
        $activeSheet->setCellValueExplicitByColumnAndRow(37, $row, $record['part']);
        $activeSheet->setCellValueExplicitByColumnAndRow(38, $row, $record['copyright_restrictions']);
        $activeSheet->setCellValueExplicitByColumnAndRow(39, $row, $record['duplicates_derivatives']);
        $activeSheet->setCellValueExplicitByColumnAndRow(40, $row, $record['related_material']);
        $activeSheet->setCellValueExplicitByColumnAndRow(41, $row, $record['condition_note']);
        $activeSheet->setCellValueExplicitByColumnAndRow(42, $row, ($record['created_on']) ? $record['created_on'] : '');
        $activeSheet->setCellValueExplicitByColumnAndRow(43, $row, ($record['updated_on']) ? $record['updated_on'] : '');
        $activeSheet->setCellValueExplicitByColumnAndRow(44, $row, $record['user_name']);

        if ($record['media_type'] == 'Audio') {
            $activeSheet->setCellValueExplicitByColumnAndRow(10, $row, $record['media_duration']);
            $activeSheet->setCellValueExplicitByColumnAndRow(13, $row, $record['base']);
            $activeSheet->setCellValueExplicitByColumnAndRow(15, $row, $record['disk_diameter']);
            $activeSheet->setCellValueExplicitByColumnAndRow(17, $row, $record['media_diameter']);
            $activeSheet->setCellValueExplicitByColumnAndRow(21, $row, $record['tape_thickness']);
            $activeSheet->setCellValueExplicitByColumnAndRow(22, $row, $record['slides']);
            $activeSheet->setCellValueExplicitByColumnAndRow(23, $row, $record['track_type']);
            $activeSheet->setCellValueExplicitByColumnAndRow(24, $row, $record['mono_stereo']);
            $activeSheet->setCellValueExplicitByColumnAndRow(25, $row, $record['noice_reduction']);
        }
        if ($record['media_type'] == 'Film') {
            $activeSheet->setCellValueExplicitByColumnAndRow(14, $row, $record['print_type']);
            $activeSheet->setCellValueExplicitByColumnAndRow(18, $row, $record['footage']);
            $activeSheet->setCellValueExplicitByColumnAndRow(20, $row, $record['color']);
            $activeSheet->setCellValueExplicitByColumnAndRow(29, $row, $record['reel_core']);
            $activeSheet->setCellValueExplicitByColumnAndRow(30, $row, $record['sound']);
            $activeSheet->setCellValueExplicitByColumnAndRow(31, $row, $record['frame_rate']);
            $activeSheet->setCellValueExplicitByColumnAndRow(32, $row, $record['acid_detection']);
            $activeSheet->setCellValueExplicitByColumnAndRow(33, $row, $record['shrinkage']);
        }
        if ($record['media_type'] == 'Video') {
            $activeSheet->setCellValueExplicitByColumnAndRow(19, $row, $record['recording_speed']);
            $activeSheet->setCellValueExplicitByColumnAndRow(26, $row, $record['cassette_size']);
            $activeSheet->setCellValueExplicitByColumnAndRow(27, $row, $record['format_version']);
            $activeSheet->setCellValueExplicitByColumnAndRow(28, $row, $record['media_duration']);
        }        
    }

}
