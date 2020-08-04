<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 9/30/2018
 * Time: 4:25 PM
 */

namespace App\Repositories;


use App\Models\Setting;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SystemRepository implements ISystemRepository
{
    public function get($key)
    {
        $setting = Setting::query()->where('key', $key)->first();
        if ($setting)
        {
            return $setting->value;
        }
        return null;
    }

    public function set($key, $value)
    {
        $setting = Setting::query()->firstOrNew(['key' => $key]);
        $setting->value = $value;
        return $setting->save();
    }

    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollbackTransaction()
    {
        DB::rollBack();
    }

    public function createOutputBasedReport($params = array())
    {
        try
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // logo
            $sheet->mergeCells('A1:A4');
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setPath('images/logo2.png'); // image path here
            $drawing->setCoordinates('A1');
            $drawing->setWidth('75');
            $drawing->setHeight('75');
            $drawing->setWorksheet($sheet);

            $sheet->mergeCells('B1:I1');
            $sheet->mergeCells('B2:I2');
            $sheet->setCellValue('B2', "UGANDA NATIONAL EXAMINATIONS BOARD");
            $sheet->getStyle("B2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->mergeCells('B3:I3');
            $sheet->setCellValue('B3', "PERFORMANCE REPORT FOR THE UNEB 2017/2020 STRATEGIC PLAN");
            $sheet->getStyle("B3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B3")->getFont()->setBold(true);

            $sheet->mergeCells('B4:I4');

            // Report period
            $sheet->setCellValue("A5", "Report End Period");
            $sheet->getStyle("A5")->getFont()->setBold(true);
            $sheet->setCellValue("B5", "Q1 2020");

            // Report frequency
            $sheet->mergeCells('D5:E5');
            $sheet->setCellValue("D5", "Reporting Frequency");
            $sheet->getStyle("D5")->getFont()->setBold(true);
            $sheet->setCellValue("F5", "Quarterly");
            // Report date
            $sheet->setCellValue("H5", "Report Date");
            $sheet->getStyle("H5")->getFont()->setBold(true);
            $sheet->setCellValue("I5", Carbon::today()->format("d-m-Y"));

            // Strategic objective
            $sheet->setCellValue("A7", "Strategic Objective");
            $sheet->getStyle("A7")->getFont()->setBold(true);
            $sheet->mergeCells('B7:I7');
            $sheet->setCellValue("B7", "1.0 TO STRENGTHEN THE CREDIBILITY, RECOGNITION AND COMPETITIVENESS OF UNEB CERTIFICATION");
            $sheet->getStyle("B7")->getFont()->setBold(true);
            $sheet->getStyle("B7")->getAlignment()->setWrapText(true);

            $sheet->setTitle('Output Based Report');
            // page setup
            $highestColumn = $sheet->getHighestColumn();
            $lastRow = 16;
            $sheet->setShowGridlines(true);
            $sheet->getPageSetup()->setPrintArea("A1:{$highestColumn}{$lastRow}");
            $sheet->getPageSetup()
                  ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                  ->setPaperSize(PageSetup::PAPERSIZE_A4);

            // page margins
            $sheet->getPageMargins()
                  ->setTop(0.10)
                  ->setRight(0.10)
                  ->setBottom(0.10)
                  ->setLeft(0.10);

            //Alignment
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            $sheet->getPageSetup()->setHorizontalCentered(true);
            $sheet->getPageSetup()->setVerticalCentered(false);

            // general font style
            $generalFontStyle = [
                'font' => [
                    'name' => 'Arial Unicode MS',
                    'size' => 12
                ]
            ];

            $spreadsheet->getDefaultStyle()->getFont()->applyFromArray($generalFontStyle);
            foreach (range("A", $sheet->getHighestDataColumn()) as $column)
            {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            if (!Storage::disk('local')->exists("public/reports"))
            {
                Storage::disk()->makeDirectory("public/reports");
            }
            $now = Carbon::now();
            $writer = new Xlsx($spreadsheet);
            $fileName = "OutputBasedReport-{$now->format('Ymd')}";
            $writer->save(public_path("storage/reports/{$fileName}.xlsx"));
        } catch (Exception $ex)
        {
            Log::error("OutputBasedReport: {$ex->getMessage()}");
        }
    }
}
