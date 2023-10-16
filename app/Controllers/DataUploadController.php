<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\Komponen7Model;

class DataUploadController extends BaseController
{
    protected $currentYear;
    protected $currentQuarter;
    protected $komponen7Model;
    protected $putaranModel;
    protected $revisiModel;

    public function __construct()
    {
        $this->currentYear = date('Y');
        $this->currentQuarter = ceil(date('n') / 3);
        $this->komponen7Model = new Komponen7Model();
        $this->putaranModel = new PutaranModel();
        $this->revisiModel = new RevisiModel();
    }

    public function index()
    {
        // cek apakah sudah login
        if (!session()->get('email')) {
            return redirect()->to('/login');
        }

        // cek apakah user memiliki akses ke halaman ini
        if (!in_array('2', session()->get('permission'))) return redirect()->to('/login');

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
        // cek apakah user memiliki akses ke halaman ini
        if (!in_array('2', session()->get('permission'))) return redirect()->to('/login');

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
}
