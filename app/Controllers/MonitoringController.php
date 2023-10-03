<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StatusModel;

class MonitoringController extends BaseController
{
    protected $statusModel;
    protected $isActive;
    protected $tahun;
    protected $id_kuartal;
    protected $putaran;

    public function __construct()
    {
        $this->statusModel = new StatusModel();

        $this->isActive = $this->statusModel->where('id_status', 1)->first()['is_active'];
        $this->tahun = $this->statusModel->where('id_status', 1)->first()['tahun'];
        $this->id_kuartal = $this->statusModel->where('id_status', 1)->first()['id_kuartal'];
        $this->putaran = $this->statusModel->where('id_status', 1)->first()['putaran'];

        // atur waktu server: jakarta
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [
            'title' => 'Rupiah | Monitoring',
            'tajuk' => 'Monitoring Putaran',
            'subTajuk' => '',
        ];

        $monitoring = [
            'isActive' => $this->isActive,
            'tahun' => $this->tahun,
            'id_kuartal' => $this->id_kuartal,
            'putaran' => $this->putaran,
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('monitoring/monitoringputaran', $monitoring);
        echo view('layouts/footer');
    }

    // Fungsi untuk mengubah status monitoring dan menambah putaran ketika dibuka
    public function updateStatus()
    {
        // mendapatkan kuartal dan tahun (periode dikurangi 1)
        if (ceil(date('n') / 3) == 1) {
            $quarter = 4;
            $year = date("Y") - 1;
        } else {
            $quarter = ceil(date('n') / 3) - 1;
            $year = date("Y");
        }

        // cek apakah buka/tutup putaran
        if ($this->isActive == 0) {
            // cek apakah perlu reset putaran (ketika masuk kuartal baru)
            if ($this->statusModel->where('tahun', $year)->where('id_kuartal', $quarter)->countAllResults() == 0) {
                $this->statusModel->update(['id_status' => 1], ['is_active' => 1, 'tahun' => $year, 'id_kuartal' => $quarter, 'putaran' => 1]);
            } else {
                $this->statusModel->update(['id_status' => 1], ['is_active' => 1, 'putaran' => $this->putaran + 1]);
            }
        } else {
            $this->statusModel->update(['id_status' => 1], ['is_active' => 0]);
        }

        $data = [
            'tahun' => $this->statusModel->where('id_status', 1)->first()['tahun'],
            'id_kuartal' => $this->statusModel->where('id_status', 1)->first()['id_kuartal'],
            'putaran' => $this->statusModel->where('id_status', 1)->first()['putaran']
        ];
        echo json_encode($data);
    }
}
