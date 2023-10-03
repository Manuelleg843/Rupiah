<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\Komponen7Model;
use \Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

class DataUploadController extends BaseController
{
    protected $currentYear;
    protected $currentQuarter;
    protected $komponen7Model;
    protected $putaranModel;
    protected $revisiModel;

    public function __construct()
    {
        $this->komponen7Model = new Komponen7Model();
        $this->putaranModel = new PutaranModel();
        $this->revisiModel = new RevisiModel();
        $this->currentYear = date('Y');
        $this->currentQuarter = ceil(date('n') / 3);
    }

    public function index()
    {
        if (!session()->get('email')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Rupiah | Upload Data',
            'tajuk' => 'Upload Data',
            'subTajuk' => 'Angka PDRB'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('uploadData/uploadAngkaPDRB');
        echo view('layouts/footer');
    }

    // Fungsi untuk mendapatkan data yang akan ditampilkan (ajax request)
    public function getData()
    {
        // Mendapatkan data dari ajax
        $jenisPDRB = $this->request->getPost('jenisPDRB');
        $kota = $this->request->getPost('kota');
        $periode = $this->request->getPost('periode');

        // Mendapatkan data dari database
        $dataPDRB = [];
        foreach ($periode as $p) {
            if ($this->revisiModel->where('periode', $p)->where('id_pdrb', $jenisPDRB)->where('id_wilayah', $kota)->countAllResults() > 0) {
                $dataPDRB[] = $this->revisiModel->getDataFinal($jenisPDRB, $kota, $p);
            } else {
                $dataPDRB[] = $this->putaranModel->getDataFinal($jenisPDRB, $kota, $p);
            }
        }

        // Mengirimkan data ke ajax
        $data = [
            'dataPDRB' => $dataPDRB,
            'komponen' => $this->komponen7Model->get_data(),
            'selectedPeriode' => $periode
        ];
        echo json_encode($data);
    }

    public function eksporPDF()
    {
        require_once ROOTPATH . 'vendor/autoload.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tableTitle']) && isset($_POST['tableContent'])) {
            // Create a PDF with DOMPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            // $options->setDpi(300);
            $dompdf = new Dompdf($options);

            $styling = "<style>
            #judulTable {
                font-weight: bold;
                font-size: 20px;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }
        
            th {
                border: 1px solid black;
                text-align: center;
            }

            td {
                border: 1px solid black;
                text-align: left;
            }
            </style>";
            $tableContent = $styling . $_POST['tableTitle'] . $_POST['tableContent'];
            $dompdf->loadHtml($tableContent);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            // Output the PDF to the client
            $dompdf->stream('exported_table.pdf', ['Attachment' => 1]);
            exit();
        }
    }
}
