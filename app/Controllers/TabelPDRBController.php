<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiskrepansiModel;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Hermawan\DataTables\DataTable;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf as PdfDompdf;
use PhpParser\Node\Stmt\TryCatch;

use function PHPSTORM_META\type;
use function PHPUnit\Framework\countOf;
use function PHPUnit\Framework\isEmpty;

class TabelPDRBController extends BaseController
{
    protected $nilaiDiskrepansi;
    protected $komponen;
    protected $putaran;

    public function __construct()
    {
        $this->nilaiDiskrepansi = new DiskrepansiModel();
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
    }

    public function index()
    {
        // //
        // $data = [
        //     'title' => 'Rupiah | Tabel Ringkasan',
        //     'tajuk' => 'tabelPDRB',
        //     'subTajuk' => 'tabelRingkasan'
        // ];

        // echo view('layouts/header', $data);
        // echo view('layouts/navbar');
        // echo view('layouts/sidebar', $data);
        // echo view('tabelPDRB/diskrepansi-ADHB');
        // echo view('layouts/footer');
    }

    public function viewTabelRingkasan()
    {
        //
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel Ringkasan PDRB Kab/Kota',
            // 'nilaiDiskrepansi' => $this->nilaiDiskrepansi->get_data(),
            // 'komponen' => $this->putaran->get_data(),
            // 'adhb'  => $this->putaran->get_data(),
            // 'adhk'  => $this->putaran->get_data(),
        ];
        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelRingkasan');
        echo view('layouts/footer');
    }

    public function viewTabelPerKota()
    {
        //
        $data = [
            'title' => 'Rupiah | Tabel Per Kota',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel PDRB Per Kota (PKRT 7 Komponen)',
            'komponen' => $this->putaran->get_data(),
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelPerKota');
        echo view('layouts/footer');
    }

    public function getData()
    {
        $jenisPDRB = $this->request->getPost('jenisPDRB');
        $kota = $this->request->getPost('kota');
        $putaran = $this->request->getPost('putaran');
        $periode = $this->request->getPost('periode');

        $data = [
            'dataPDRB' => $this->putaran->getDataHistory($jenisPDRB, $kota, $putaran, $periode),
            'komponen' => $this->komponen->get_data(),
            'selectedPeriode' => $periode
        ];

        echo json_encode($data);
    }

    public function viewTabelHistoryPutaran()
    {
        if (!session()->get('email')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Rupiah | Tabel History Putaran',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel History Putaran',
            'putaran' => $this->putaran->getPutaranTerakhir(),
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelHistoryPutaran', $data);
        echo view('layouts/footer');
    }

    public function getAllPeriode()
    {
        $allPeriode = $this->putaran->getAllPeriode();

        return $allPeriode;
    }

    public function exportExcel($tableSelected, $jenisPDRB, $kota, $putaran, $periode)
    {

        $periodeArr = explode(",", $periode);

        $komponen = $this->komponen->get_data();

        // get filter 
        $dataPDRB = $this->putaran->getData($jenisPDRB, $kota, $putaran, $periodeArr);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $putaran == 'null' ?  $title = $tableSelected . " - " . $kota . " - Semua Putaran" :   $title = $tableSelected . " - " . $kota . " - Putaran " . $putaran;

        // header table 
        $sheetData = [];
        $columnHeader = ['Komponen'];
        foreach ($periodeArr as $col) {
            array_push($columnHeader, $col);
        }
        array_push($sheetData, $columnHeader);

        // isi tabel 
        $komponenData = [];
        $nilaiPDRB = [];
        $temp = -1;

        // mengubah tipe data jadi array
        // $dataPDRBarray = array_push($dataPDRBarray, $dataPDRB);
        foreach ($komponen as $rows) {
            for ($col = 0; $col < sizeof($columnHeader); $col++) {
                if ($col == 0) {
                    if ($rows->id_komponen == 1 || $rows->id_komponen == 2 || $rows->id_komponen == 3 || $rows->id_komponen == 4 || $rows->id_komponen == 5 || $rows->id_komponen == 6 || $rows->id_komponen == 7 || $rows->id_komponen == 8) {
                        $komponen = $rows->id_komponen . ". " . $rows->komponen;
                    } elseif ($rows->id_komponen == 9) {
                        $komponen = $rows->komponen;
                    } else {
                        $komponen = "     " . $rows->id_komponen . ". " . $rows->komponen;
                    };
                    array_push($nilaiPDRB, $komponen);
                } else {
                    $temp++;
                    array_push($nilaiPDRB, $dataPDRB[$temp]->nilai);
                }
            }
            array_push($komponenData, $nilaiPDRB);
            $nilaiPDRB = [];
        }
        array_push($sheetData, $komponenData);

        $sheet->fromArray([$title]);
        $sheet->fromArray($sheetData[0], null, 'A3');
        $sheet->fromArray($sheetData[1], null, 'A4');


        // mergeCell dan bold judul tabel
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true);


        // bold dan center table header 
        foreach ($sheet->getColumnIterator() as $column) {
            $row = $column->getColumnIndex() . '3';
            $sheet->getStyle($row)->getFont()->setBold(true);
            $sheet->getStyle($row)->getAlignment()->setHorizontal('center');
        }

        // table border`
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ]
            ]
        ];

        // setting column width, border, number format 
        foreach ($sheet->getColumnIterator() as $column) {
            foreach ($sheet->getRowIterator() as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    $cell->getStyle()->applyFromArray($styleArray);
                }
                // $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            $column->getColumnIndex() == 'A' ? " " : $sheet->getStyle($column->getColumnIndex())->getNumberFormat()->setFormatCode('#,##0.00');
        }
        // Simpan sebagai file Excel
        $filename = $title . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        $writer->save('php://output');
        exit();
    }

    public function exportPDF($tableSelected, $jenisPDRB, $kota, $putaran, $periode)
    {

        $periodeArr = explode(",", $periode);

        $komponen = $this->komponen->get_data();
        // get filter 
        $dataPDRB = $this->putaran->getData($jenisPDRB, $kota, $putaran, $periodeArr);

        // create dompdf object 
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // Load konten HTML ke Dompdf
        $data = [
            'subTajuk' => 'Tabel History Putaran',
            'dataPDRB' => $this->putaran->getData($jenisPDRB, $kota, $putaran, $periodeArr),
            'komponen' => $this->komponen->get_data(),
            'selectedPeriode' => $periode
        ];

        // generate html untuk tabel 
        // $html = ' <div class="table-responsive d-flex text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">';
        // $html .= '<table id="PDRBTable" class="table table-bordered table-hover">';
        // $html .= '<thead class="text-center table-primary sticky-top"><tr><th colspan="2">Komponen</th><th>2023Q1</th></tr></thead>';
        // $html .= '<tbody>';
        // foreach ($dataPDRB as $row) {
        //     $html .= '<tr>';
        //     if ($row->id_komponen == 9) {
        //         $html .= '<td colspan="2">';
        //         $html .= $row->id_komponen  . '.  ' . $row->komponen  . '</td>';
        //     } else {
        //         if ($row->id_komponen == 1 && $row->id_komponen == 2 && $row->id_komponen == 3 && $row->id_komponen == 4 && $row->id_komponen == 5 && $row->id_komponen == 6 && $row->id_komponen == 7 && $row->id_komponen == 8) {
        //             $html .= '<td colspan="2">' . $row->id_komponen . ". " . $row->komponen . '</td>';
        //         } else {
        //             $html .= '<td colspan="2" class="pl-4">' . $row->id_komponen  . '.  ' . $row->komponen  . '</td>';
        //         }
        //     }

        //     // $html .= '<tr>';
        //     // $html .= '<td colspan="2">';
        //     // $html .= '<td class="text-right">' . $row->id_komponen  . '.   ' . $row->komponen  . '</td>';
        //     $html .= '<td class="text-right">' . number_format($row->nilai, 2, ',', '.') . '</td>';
        //     $html .= '</tr>';
        // }
        // $html .= '</table>';
        // echo view('tabelPDRB/table_view', $data);
        // $html = $this->response->getBody();
        $html = view('tabelPDRB/table_view', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        ob_end_clean();
        $dompdf->stream('data_PDRB.pdf', array('Attachment' => 0));
    }
}
