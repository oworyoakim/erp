<?php

namespace App\Http\Controllers\Spms;

use App\ErpHelper;
use App\Http\Controllers\GatewayController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpmsReportsGateway extends GatewayController
{
    /**
     * @var Filesystem
     */
    private $files;

    public function __construct(Filesystem $files)
    {
        $this->urlEndpoint = env('SPMS_APP_URL') . '/v1/reports';
        $this->files = $files;
    }

    public function summaryStrategyReport(Request $request)
    {
        try
        {
            $params = $request->only(['planId']);
            $params['reportType'] = 'summary';

            $responseData = $this->generateStrategyReportData($params);

            //dd($responseData);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function detailedStrategyReport(Request $request)
    {
        try
        {
            $params = $request->only(['planId', 'reportPeriodId']);
            $params['reportType'] = 'detailed';

            $responseData = $this->generateStrategyReportData($params);

            //dd($responseData);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function activityReport(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->generateActivityReportData($params);

            //dd($responseData);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function directivesAndResolutionsReport(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->generateDirectivesAndResolutionsReportData($params);

            //dd($responseData);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @param array $params
     *
     * @return \stdClass
     * @throws Exception
     */
    private function generateStrategyReportData(array $params)
    {
        $responseData = $this->get("{$this->urlEndpoint}/strategy-report", $params);
        // the logo
        $logo = settings()->get('company_logo');
        if (!empty($logo))
        {
            $logo = ltrim($logo, '/');
        } else
        {
            $logo = "storage/images/logo2.png";
        }
        $responseData['companyLogo'] = base64_encode(File::get(public_path($logo)));
        $responseData = ErpHelper::arrayToObject($responseData);
        if (!empty($params['reportType']) && $params['reportType'] == 'summary')
        {
            $html = View::make('spms.reports.monitor-strategy-summary', [
                'reportData' => $responseData
            ])->render();
            $plan = str_replace('/', '-', $responseData->plan);
            $plan = Str::slug($plan);
            $fileName = "{$plan}_summary_report.pdf";
        } else
        {
            $html = View::make('spms.reports.monitor-strategy', [
                'reportData' => $responseData
            ])->render();
            $plan = str_replace('/', '-', $responseData->plan);
            $plan = Str::slug($plan);
            $fileName = "{$plan}_{$responseData->reportPeriod->startDate}_to_{$responseData->reportPeriod->endDate}.pdf";
        }

        $filePath = "storage/reports/{$fileName}";
        /*
        // generate the pdf using dompdf
        $options = new Options();
        $options->set('isRemoteEnabled',true);
        $dompdf = new Dompdf( $options );
        $dompdf->setPaper('letter', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();
        // save the pdf
        //dd($dompdf->output());
        //Storage::put(public_path($filePath), $dompdf->output());
        File::put(public_path($filePath), $dompdf->output());
        */
        // generate the pdf using mpdf
        $mpdf = new Mpdf([
            'orientation' => 'L'
        ]);
        $footer = "TABLE HEADER KEYS => MA: Measured As (CT: Count, %: %age), TA: Target, AV: Actual Value, PA: %age of Achievement, VA: Variance";
        $mpdf->SetFooter($footer);
        $mpdf->WriteHTML($html);
        $mpdf->Output(public_path($filePath), 'F');

        $responseData->filePath = "/{$filePath}";

        return $responseData;
    }

    /**
     * @param array $params
     *
     * @return \stdClass
     * @throws Exception
     */
    private function generateActivityReportData(array $params)
    {
        $responseData = $this->get("{$this->urlEndpoint}/activity-report", $params);
        $directorateData = null;
        if (!empty($responseData['directorateId']))
        {
            $directorateData = $this->getDirectorate($responseData['directorateId']);
        }

        if (!empty($directorateData['title']))
        {
            $responseData['responsibilityCenter'] = $directorateData['title'];
        } else
        {
            $responseData['responsibilityCenter'] = null;
        }
        // the logo
        $logo = settings()->get('company_logo');
        if (!empty($logo))
        {
            $logo = ltrim($logo, '/');
        } else
        {
            $logo = "storage/images/logo2.png";
        }
        $responseData['companyLogo'] = base64_encode(File::get(public_path($logo)));
        $responseData = ErpHelper::arrayToObject($responseData);
        //dd($responseData->reportPeriod);
        $html = View::make('spms.reports.monitor-activity', [
            'reportData' => $responseData
        ])->render();

        $plan = str_replace('/', '-', $responseData->plan);
        $plan = Str::slug($plan);
        $dirAndRes = Str::slug('activities-report');
        $workPlan = str_replace('/', '-', $responseData->workPlan->title);
        $workPlan = Str::slug($workPlan);
        $fileName = "{$plan}_{$dirAndRes}_{$workPlan}.pdf";

        $filePath = "storage/reports/{$fileName}";

        // generate the pdf using mpdf
        $mpdf = new Mpdf([
            'orientation' => 'L'
        ]);
        $footer = "TABLE HEADER KEYS => MA: Measured As (CT: Count, %: %age), TA: Target, AV: Actual Value, PA: %age of Achievement, VA: Variance";
        $mpdf->SetFooter($footer);
        $mpdf->WriteHTML($html);
        $mpdf->Output(public_path($filePath), 'F');

        $responseData->filePath = "/{$filePath}";

        return $responseData;
    }

    /**
     * @param array $params
     *
     * @return \stdClass
     * @throws Exception
     */
    private function generateDirectivesAndResolutionsReportData(array $params)
    {
        $responseData = $this->get("{$this->urlEndpoint}/directives-and-resolutions-report", $params);
        $directorates = $this->getDirectorates();
        $directorates = collect($directorates);

        foreach ($responseData['directivesAndResolutions'] as &$directiveAndResolution)
        {
            $directorateData = null;
            if (!empty($directiveAndResolution['responsibilityCentreId']))
            {
                $directorateData = $directorates->firstWhere('id', $directiveAndResolution['responsibilityCentreId']);
                if (!empty($directorateData['title']))
                {
                    $directiveAndResolution['responsibilityCenter'] = $directorateData['title'];
                } else
                {
                    $directiveAndResolution['responsibilityCenter'] = null;
                }
            }
        }

        // the logo
        $logo = settings()->get('company_logo');
        if (!empty($logo))
        {
            $logo = ltrim($logo, '/');
        } else
        {
            $logo = "storage/images/logo2.png";
        }
        $responseData['companyLogo'] = base64_encode(File::get(public_path($logo)));
        $responseData = ErpHelper::arrayToObject($responseData);
        //dd($responseData->reportPeriod);
        $html = View::make('spms.reports.monitor-directives-and-resolutions', [
            'reportData' => $responseData
        ])->render();

        $plan = str_replace('/', '-', $responseData->plan);
        $plan = Str::slug($plan);
        $dirAndRes = Str::slug('directives-and-resolutions-report');
        $workPlan = str_replace('/', '-', $responseData->workPlan->title);
        $workPlan = Str::slug($workPlan);
        $fileName = "{$plan}_{$dirAndRes}_{$workPlan}.pdf";

        $filePath = "storage/reports/{$fileName}";
        /*
        // generate the pdf using dompdf
        $options = new Options();
        $options->set('isRemoteEnabled',true);
        $dompdf = new Dompdf( $options );
        $dompdf->setPaper('letter', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();
        // save the pdf
        //dd($dompdf->output());
        //Storage::put(public_path($filePath), $dompdf->output());
        File::put(public_path($filePath), $dompdf->output());
        */
        // generate the pdf using mpdf
        $mpdf = new Mpdf([
            'orientation' => 'L'
        ]);
        $footer = "TABLE HEADER KEYS => MA: Measured As (CT: Count, %: %age), TA: Target, AV: Actual Value, PA: %age of Achievement, VA: Variance";
        $mpdf->SetFooter($footer);
        $mpdf->WriteHTML($html);
        $mpdf->Output(public_path($filePath), 'F');

        $responseData->filePath = "/{$filePath}";

        return $responseData;
    }

    public function strategyReportExcel(Request $request)
    {
        try
        {
            $params = $request->only(['planId', 'reportPeriodId']);

            $responseData = $this->get("{$this->urlEndpoint}/strategy-report", $params);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setShowGridlines(false);
            $sheet->setTitle("Strategy Performance Report");
            $sheet->mergeCells("A1:A3");

            $rowHeight = 18;

            // the logo
            $logo = settings()->get('company_logo');
            if (!empty($logo))
            {
                $logo = ltrim($logo, '/');
            } else
            {
                $logo = "storage/images/logo2.png";
            }
            $drawing = new Drawing();
            $drawing->setCoordinates("A1");
            $drawing->setPath($logo);
            $drawing->setOffsetY(11);
            $drawing->setOffsetX(5);
            //$drawing->setHeight(50);
            //$drawing->setWidth(50);
            $drawing->setWorksheet($sheet);
            $sheet->getStyle("A1")
                  ->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                  ->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->mergeCells("B1:T1");
            $sheet->setCellValue("B1", "UGANDA NATIONAL EXAMINATIONS BOARD");
            $sheet->getStyle("B1:T1")
                  ->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                  ->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("B1")
                  ->getFont()
                  ->setBold(true)
                  ->setSize(14);
            $sheet->getRowDimension(1)->setRowHeight($rowHeight);

            $sheet->mergeCells("B2:T2");
            $sheet->setCellValue("B2", "PERFORMANCE REPORT FOR THE UNEB " . strtoupper($responseData['plan']));
            $sheet->getStyle("B2:T2")
                  ->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                  ->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("B2")
                  ->getFont()
                  ->applyFromArray([
                      'size' => 13,
                      'bold' => true,
                  ]);
            $sheet->getRowDimension(2)->setRowHeight($rowHeight);

            $sheet->mergeCells("B3:T3");

            $sheet->mergeCells("A4:C4");
            $sheet->setCellValue("A4", "Report End Period")
                  ->getStyle("A4:C4")
                  ->getFont()
                  ->applyFromArray([
                      'bold' => true,
                      'size' => 12,
                  ]);
            $sheet->mergeCells("D4:F4");
            $sheet->setCellValue("D4", "{$responseData['dateParams']['currentQuarter']['name']} {$responseData['dateParams']['financialYear']}")
                  ->getStyle("D4:F4")
                  ->applyFromArray([
                      'size' => 12,
                  ]);

            $sheet->mergeCells("H4:I4");

            $sheet->mergeCells("J4:L4");
            $sheet->setCellValue("J4", "Reporting Frequency")
                  ->getStyle("J4:L4")
                  ->getFont()
                  ->applyFromArray([
                      'bold' => true,
                      'size' => 12,
                  ]);
            $sheet->mergeCells("M4:O4");
            $sheet->setCellValue("M4", ucfirst($responseData['reportFrequency']))
                  ->getStyle("M4:O4")
                  ->applyFromArray([
                      'size' => 12,
                  ]);

            $sheet->mergeCells("Q4:R4");
            $sheet->setCellValue("Q4", "Report Date")
                  ->getStyle("Q4:R4")
                  ->getFont()
                  ->applyFromArray([
                      'bold' => true,
                      'size' => 12,
                  ]);
            $sheet->mergeCells("S4:T4");
            $sheet->setCellValue("S4", $responseData['reportDate'])
                  ->getStyle("S4:T4")
                  ->applyFromArray([
                      'size' => 12,
                  ]);

            $sheet->getRowDimension(4)->setRowHeight($rowHeight);
            $sheet->getStyle("A4:T4")
                  ->getBorders()
                  ->getTop()
                  ->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A4:T4")
                  ->getBorders()
                  ->getBottom()
                  ->setBorderStyle(Border::BORDER_THIN);

            // report data
            $rowIndex = 5;
            foreach ($responseData['objectives'] as $objective)
            {
                $sheet->mergeCells("A{$rowIndex}:T{$rowIndex}");
                $rowIndex++;
                $sheet->mergeCells("A{$rowIndex}:T{$rowIndex}");
                $sheet->setCellValue("A{$rowIndex}", "Strategic Objective: {$objective['name']}");
                $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                      ->getFont()
                      ->applyFromArray([
                          'size' => 13,
                          'bold' => true,
                      ]);
                $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                      ->getBorders()
                      ->getAllBorders()
                      ->setBorderStyle(Border::BORDER_THIN);
                $sheet->getRowDimension($rowIndex)->setRowHeight($rowHeight);
                foreach ($objective['interventions'] as $intervention)
                {
                    $rowIndex++;
                    $sheet->mergeCells("A{$rowIndex}:T{$rowIndex}");
                    $sheet->setCellValue("A{$rowIndex}", "Strategic Intervention: {$intervention['name']}");
                    $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                          ->getFont()
                          ->applyFromArray([
                              'size' => 12,
                              'bold' => true,
                          ]);
                    $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                          ->getBorders()
                          ->getBottom()
                          ->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getRowDimension($rowIndex)->setRowHeight($rowHeight);
                    $rowIndex++;
                    if (count($intervention['outputs']))
                    {
                        $sheet->mergeCells("A{$rowIndex}:E{$rowIndex}");
                        $sheet->setCellValue("A{$rowIndex}", "Output");
                        $sheet->mergeCells("F{$rowIndex}:J{$rowIndex}");
                        $sheet->setCellValue("F{$rowIndex}", "Indicator");
                        $sheet->setCellValue("K{$rowIndex}", "Measured As");
                        $sheet->setCellValue("L{$rowIndex}", "Target");
                        $sheet->setCellValue("M{$rowIndex}", "Actual");
                        $sheet->setCellValue("N{$rowIndex}", "Achieved(%)");
                        $sheet->setCellValue("O{$rowIndex}", "Variance");
                        $sheet->mergeCells("P{$rowIndex}:T{$rowIndex}");
                        $sheet->setCellValue("P{$rowIndex}", "Comment");
                        $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                              ->getFont()
                              ->setBold(true);
                        $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                              ->getBorders()
                              ->getBottom()
                              ->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getStyle("F{$rowIndex}")
                              ->getBorders()
                              ->getLeft()
                              ->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getRowDimension($rowIndex)->setRowHeight($rowHeight);
                        foreach ($intervention['outputs'] as $output)
                        {
                            $rowIndex++;
                            $numIndicators = count($output['indicators']);
                            if ($numIndicators)
                            {
                                $lastIndicatorRow = $rowIndex + $numIndicators - 1;
                                $sheet->mergeCellsByColumnAndRow(1, $rowIndex, 5, $lastIndicatorRow);
                                $sheet->setCellValue("A{$rowIndex}", $output['name']);
                                $sheet->getStyle("A{$rowIndex}")
                                      ->getAlignment()
                                      ->setWrapText(true)
                                      ->setVertical(Alignment::VERTICAL_CENTER);
                                $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                                      ->getBorders()
                                      ->getBottom()
                                      ->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getStyle("F{$rowIndex}")
                                      ->getBorders()
                                      ->getLeft()
                                      ->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getRowDimension($rowIndex - 1)->setRowHeight($rowHeight);
                                foreach ($output['indicators'] as $indicator)
                                {
                                    $sheet->mergeCells("F{$rowIndex}:J{$rowIndex}");
                                    $sheet->setCellValue("F{$rowIndex}", $indicator['name']);
                                    $sheet->getStyle("F{$rowIndex}:J{$rowIndex}")
                                          ->getAlignment()
                                          ->setWrapText(true);
                                    $unit = ($indicator['unit'] == 'percent') ? "Percentage" : "Count";
                                    $sheet->setCellValue("K{$rowIndex}", $unit);
                                    $sheet->setCellValue("L{$rowIndex}", $indicator['target']);
                                    $sheet->setCellValue("M{$rowIndex}", $indicator['actual']);
                                    $sheet->setCellValue("N{$rowIndex}", $indicator['achieved']);
                                    $sheet->setCellValue("O{$rowIndex}", $indicator['variance']);
                                    $sheet->mergeCells("P{$rowIndex}:T{$rowIndex}");
                                    $sheet->setCellValue("P{$rowIndex}", $indicator['comments']);
                                    $sheet->getStyle("P{$rowIndex}:T{$rowIndex}")
                                          ->getFont()
                                          ->setSize(9);
                                    $sheet->getStyle("P{$rowIndex}:T{$rowIndex}")
                                          ->getAlignment()
                                          ->setWrapText(true);
                                    $sheet->getColumnDimension("P")
                                          ->setWidth(10);
                                    $sheet->getColumnDimension("Q")
                                          ->setWidth(10);
                                    $sheet->getColumnDimension("R")
                                          ->setWidth(10);
                                    $sheet->getColumnDimension("S")
                                          ->setWidth(10);
                                    $sheet->getColumnDimension("T")
                                          ->setWidth(10);

                                    $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                                          ->getBorders()
                                          ->getBottom()
                                          ->setBorderStyle(Border::BORDER_THIN);
                                    $sheet->getStyle("F{$rowIndex}")
                                          ->getBorders()
                                          ->getLeft()
                                          ->setBorderStyle(Border::BORDER_THIN);
                                    $sheet->getStyle("L{$rowIndex}:O{$rowIndex}")
                                          ->getAlignment()
                                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                    $rowIndex++;
                                }
                                $rowIndex--;
                            } else
                            {
                                $sheet->mergeCells("A{$rowIndex}:E{$rowIndex}");
                                $sheet->setCellValue("A{$rowIndex}", $output['name']);
                                $sheet->getStyle("A{$rowIndex}")
                                      ->getAlignment()
                                      ->setWrapText(true)
                                      ->setVertical(Alignment::VERTICAL_CENTER);
                                $sheet->mergeCells("F{$rowIndex}:T{$rowIndex}");
                                $sheet->setCellValue("F{$rowIndex}", "No indicators!");
                                $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                                      ->getBorders()
                                      ->getBottom()
                                      ->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getStyle("F{$rowIndex}")
                                      ->getBorders()
                                      ->getLeft()
                                      ->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getRowDimension($rowIndex)->setRowHeight($rowHeight);
                            }
                        }
                    } else
                    {
                        $sheet->mergeCells("A{$rowIndex}:T{$rowIndex}");
                        $sheet->setCellValue("A{$rowIndex}", "No outputs!");
                        $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                              ->getBorders()
                              ->getAllBorders()
                              ->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getRowDimension($rowIndex)->setRowHeight($rowHeight);
                    }


                }

            }

            $sheet->getPageSetup()->setPrintArea("A1:T{$rowIndex}");

            $sheet->getStyle("A5:T5")
                  ->getBorders()
                  ->getHorizontal()
                  ->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A7:T7")
                  ->getBorders()
                  ->getHorizontal()
                  ->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A8:T8")
                  ->getBorders()
                  ->getHorizontal()
                  ->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A9:T9")
                  ->getBorders()
                  ->getHorizontal()
                  ->setBorderStyle(Border::BORDER_NONE);
            $sheet->getStyle("A10:T10")
                  ->getBorders()
                  ->getTop()
                  ->setBorderStyle(Border::BORDER_THIN);

            // page margins
            $sheet->getPageMargins()
                  ->setTop(0.10)
                  ->setRight(0.10)
                  ->setBottom(0.10)
                  ->setLeft(0.10);
            // outer borders
            $sheet->getStyle("A1:A{$rowIndex}")
                  ->getBorders()
                  ->getLeft()
                  ->setBorderStyle(Border::BORDER_THIN);

            $sheet->getStyle("A{$rowIndex}:T{$rowIndex}")
                  ->getBorders()
                  ->getBottom()
                  ->setBorderStyle(Border::BORDER_THICK);

            $sheet->getStyle("T1:T{$rowIndex}")
                  ->getBorders()
                  ->getRight()
                  ->setBorderStyle(Border::BORDER_THICK);
            // page setup
            $sheet->getPageSetup()
                  ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                  ->setPaperSize(PageSetup::PAPERSIZE_A4);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->getColumnDimension('O')->setAutoSize(true);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
            $sheet->getPageSetup()->setHorizontalCentered(true);
            $sheet->getPageSetup()->setVerticalCentered(false);
            // create writer
            $writer = new Xlsx($spreadsheet);
            if (!$this->files->isDirectory(public_path("storage/reports")))
            {
                $this->files->makeDirectory(public_path("storage/reports"));
            }
            $fileName = "spms-strategy-performance-report";
            // Save to disk
            $writer->save(public_path("storage/reports/{$fileName}.xlsx"));
            // download
            return response($this->files->get(public_path("storage/reports/{$fileName}.xlsx")), 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '.xlsx"'
            ]);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
