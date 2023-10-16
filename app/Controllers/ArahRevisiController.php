<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\WilayahModel;

class ArahRevisiController extends BaseController
{
    protected $komponenModel;
    protected $putaranModel;
    protected $revisiModel;
    protected $wilayahModel;

    public function __construct()
    {
        $this->komponenModel = new Komponen7Model();
        $this->putaranModel = new PutaranModel();
        $this->revisiModel = new RevisiModel();
        $this->wilayahModel = new WilayahModel();
    }

    public function index()
    {
        // cek apakah sudah login
        if (!session()->get('email')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Rupiah | Arah Revisi',
            'tajuk' => 'Arah Revisi',
            'subTajuk' => 'Arah Revisi Kota',
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/arahRevisi/arahRevisi');
        echo view('layouts/footer');
    }

    // Fungsi untuk mendapatkan data yang akan ditampilkan (ajax request)
    public function getData()
    {
        // Mendapatkan data dari ajax
        $jenisPDRB = $this->request->getPost('jenisTable');
        $kota = $this->request->getPost('kota');
        $periode = $this->request->getPost('periode');
        if (empty($periode)) {
            $getPeriode = function () {
                $month = date('n');
                $quarter = ceil($month / 3);

                $year = date('Y');

                if ($quarter == 1) {
                    $previousQuarter = 4;
                    $year = $year - 1;
                } else {
                    $previousQuarter = $quarter - 1;
                }
                return $year . 'Q' . $previousQuarter;
            };

            $periode = [$getPeriode()];
        }

        // Mendapatkan data dari database
        $dataArahRevisi = [];
        foreach ($periode as $p) { // mengambil data (1) putaran dan (2) revisi untuk dibandingkan
            $dataArahRevisi[] = $this->putaranModel->getDataFinal($jenisPDRB, $kota, $p);
            if ($this->revisiModel->where('periode', $p)->where('id_pdrb', $jenisPDRB)->where('id_wilayah', $kota)->countAllResults() > 0) {
                $dataArahRevisi[] = $this->revisiModel->getDataFinal($jenisPDRB, $kota, $p);
            } else {
                $dataArahRevisi[] = $this->putaranModel->getDataFinal($jenisPDRB, $kota, $p);
            }
        }
        $wilayah = $this->wilayahModel->getAll();

        // Mengirimkan data ke ajax
        $data = [
            'dataArahRevisi' => $dataArahRevisi,
            'komponen' => $this->komponenModel->get_data(),
            'selectedPeriode' => $periode,
            'wilayah' => $wilayah,
        ];
        echo json_encode($data);
    }
}
