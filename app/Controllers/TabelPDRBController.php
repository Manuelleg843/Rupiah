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
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf as PdfDompdf;

use function PHPUnit\Framework\isEmpty;

$data = [
    'id_satker' => '3100',
];
session()->set($data);

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
        //
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'tabelPDRB',
            'subTajuk' => 'tabelRingkasan'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelRingkasan');
        echo view('layouts/footer');
    }

    // // get filter
    public function getData()
    {
        $filter = array();

        if (!empty($_GET['jenisPDRB'])) {
            $filter['jenisPDRB'] = array(
                'id_pdrb' => $_GET['jenisPDRB'],
            );
        };

        // dd($filter);
        return $filter;
    }

    public function viewTabelRingkasan()
    {
        //
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel Ringkasan PDRB Kab/Kota',
            // 'nilaiDiskrepansi' => $this->nilaiDiskrepansi->get_data(),
            'komponen' => $this->putaran->get_data(),
            'adhb'  => $this->putaran->get_data(),
            'adhk'  => $this->putaran->get_data(),
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

    public function viewTabelHistoryPutaran()
    {
        $dataPDRB = $this->putaran
            ->select()
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['periode', 'putaran.id_komponen', 'komponen_7.komponen', 'id_wilayah', 'id_pdrb', 'tahun', 'putaran', 'nilai', 'periode'])
            ->orderBy('putaran.id_komponen');
        if ($jenisPDRB = $this->request->getGet('jenisPDRB')) {
            $dataPDRB = $dataPDRB->where('id_pdrb', $jenisPDRB);
        }

        $data = [
            'title' => 'Rupiah | Tabel History Putaran',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel History Putaran',
            'dataPDRB' => $dataPDRB,
            // 'komponen' => $this->putaran->get_data(),
            'selectedPeriode' => $this->request->getVar('columns'),
            'putaran' => $this->putaran->getPutaranTerakhir(),
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelHistoryPutaran', $data);
        echo view('layouts/footer');
    }

    // public function viewTabelHistoryPutaran()
    // {
    //     // get id_wilayah from session
    //     if (session()->has('id_satker')) {
    //         $wilayah = session('id_satker');
    //     }

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $pilihan = $_POST['jenisPDRB'];
    //     }

    //     $data = [
    //         'title' => 'Rupiah | Tabel History Putaran',
    //         'tajuk' => 'Tabel PDRB',
    //         'subTajuk' => 'Tabel History Putaran',
    //         'komponen' => $this->putaran->get_data(),
    //         'selectedPeriode' => $this->request->getVar('columns'),
    //         'putaran' => $this->putaran->getPutaranTerakhir(),
    //     ];

    //     echo view('layouts/header', $data);
    //     echo view('layouts/navbar');
    //     echo view('layouts/sidebar', $data);
    //     echo view('tabelPDRB/tabelHistoryPutaran', $data);
    //     echo view('layouts/footer');
    // }

    public function getDataHistory()
    {
        $data = [
            'pdrb' => $this->putaran->get_pdrb(),
        ];

        dd($data['pdrb']);
    }

    // export excel
    public function exportExcel()
    {
        $dataPDRB = $this->putaran->get_data();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        // $activeWorksheet->setCellValue('A1', 'Komponen');
        $activeWorksheet->setCellValue('A3', 'Komponen');
        $activeWorksheet->setCellValue('B3', '2023Q1');

        $column = 4;
        foreach ($dataPDRB as $key => $value) {
            if ($value->id_komponen == 1 || $value->id_komponen == 2 || $value->id_komponen == 3 || $value->id_komponen == 4 || $value->id_komponen == 5 || $value->id_komponen == 6 || $value->id_komponen == 7 || $value->id_komponen == 8 || $value->id_komponen == 9) {
                $komponen = $value->id_komponen . ". " . $value->komponen;
            } else {
                $komponen = "     " . $value->id_komponen . ". " . $value->komponen;
            };
            $activeWorksheet->setCellValue('A' . $column, $komponen);
            $activeWorksheet->setCellValue('B' . $column, $value->nilai);
            $column++;
        }

        // styling excel 
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ]
            ]
        ];
        $activeWorksheet->getStyle('A3:B' . ($column - 1))->applyFromArray($styleArray);
        $activeWorksheet->getColumnDimension('A')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('B')->setAutoSize(true);
        $activeWorksheet->getStyle('A3:B3')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A3:B3')->getAlignment()->setHorizontal('center');
        $activeWorksheet->getStyle('B4:B21')->getAlignment()->setHorizontal('right');


        $writer = new Xlsx($spreadsheet);
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename=dataPDRB.xlsx');

        $writer->save('php://output');
        exit();
    }

    public function exportPDF()
    {

        $data = [
            'komponen' => $this->putaran->get_data(),
        ];

        $dataPDRB = $this->putaran->get_data();

        // create dompdf object 
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // generate html untuk tabel 
        $html = ' <div class="table-responsive d-flex text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">';
        $html .= '<table id="PDRBTable" class="table table-bordered table-hover">';
        $html .= '<thead class="text-center table-primary sticky-top"><tr><th colspan="2">Komponen</th><th>2023Q1</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($dataPDRB as $row) {
            $html .= '<tr>';
            if ($row->id_komponen == 9) {
                $html .= '<td colspan="2">';
                $html .= $row->id_komponen  . '.  ' . $row->komponen  . '</td>';
            } else {
                if ($row->id_komponen == 1 && $row->id_komponen == 2 && $row->id_komponen == 3 && $row->id_komponen == 4 && $row->id_komponen == 5 && $row->id_komponen == 6 && $row->id_komponen == 7 && $row->id_komponen == 8) {
                    $html .= '<td colspan="2">' . $row->id_komponen . ". " . $row->komponen . '</td>';
                } else {
                    $html .= '<td colspan="2" class="pl-4">' . $row->id_komponen  . '.  ' . $row->komponen  . '</td>';
                }
            }

            // $html .= '<tr>';
            // $html .= '<td colspan="2">';
            // $html .= '<td class="text-right">' . $row->id_komponen  . '.   ' . $row->komponen  . '</td>';
            $html .= '<td class="text-right">' . number_format($row->nilai, 2, ',', '.') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        // $html = view('tabelPDRB/tabelHistoryPutaran', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('data_PDRB.pdf');
    }

    // create table 
    public function table_data()
    {
        $listing = $this->putaran->get_dataTables();

        $data = array();
        foreach ($listing as $key) {
            $row = array();
            $row[] = $key->id_komponen;
            $row[] = $key->nilai;
            $data[] = $row;
        }

        $output = array(
            // "draw" => $_POST['draw'],
            // "start" =>,
            "data" => $data
        );
        echo json_encode($output);
    }
}
