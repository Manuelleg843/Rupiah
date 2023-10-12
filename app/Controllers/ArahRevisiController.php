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
        //
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

    public function getData()
    {
        $jenisPDRB = $this->request->getPost('jenisTable');
        $kota = $this->request->getPost('kota');
        $periode = $this->request->getPost('periode');

        $dataArahRevisi = [];
        foreach ($periode as $p) {
            $dataArahRevisi[] = $this->putaranModel->getDataFinal($jenisPDRB, $kota, $p);
            if ($this->revisiModel->where('periode', $p)->where('id_pdrb', $jenisPDRB)->where('id_wilayah', $kota)->countAllResults() > 0) {
                $dataArahRevisi[] = $this->revisiModel->getDataFinal($jenisPDRB, $kota, $p);
            } else {
                $dataArahRevisi[] = $this->putaranModel->getDataFinal($jenisPDRB, $kota, $p);
            }
        }
        $wilayah = $this->wilayahModel->getAll();

        $data = [
            'dataArahRevisi' => $dataArahRevisi,
            'komponen' => $this->komponenModel->get_data(),
            'selectedPeriode' => $periode,
            'wilayah' => $wilayah,
        ];

        echo json_encode($data);
    }
}
