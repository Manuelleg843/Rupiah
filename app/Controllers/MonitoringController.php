<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PutaranModel;
use App\Models\StatusModel;
use App\Models\WilayahModel;

class MonitoringController extends BaseController
{
    protected $putaranModel;
    protected $statusModel;
    protected $wilayahModel;
    protected $isActive;
    protected $tahun;
    protected $id_kuartal;
    protected $putaran;

    public function __construct()
    {
        $this->putaranModel = new PutaranModel();
        $this->statusModel = new StatusModel();
        $this->wilayahModel = new WilayahModel();

        $this->isActive = $this->statusModel->where('id_status', 1)->first()['is_active'];
        $this->tahun = $this->statusModel->where('id_status', 1)->first()['tahun'];
        $this->id_kuartal = $this->statusModel->where('id_status', 1)->first()['id_kuartal'];
        $this->putaran = $this->statusModel->where('id_status', 1)->first()['putaran'];

        // atur waktu server: jakarta
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        // cek apakah sudah login
        if (!session()->get('email')) {
            return redirect()->to('/login');
        };

        $data = [
            'title' => 'Rupiah | Monitoring',
            'tajuk' => 'Monitoring Putaran',
            'subTajuk' => '',
        ];

        $wilayah = array_map('current', $this->wilayahModel->orderBy('id_wilayah', 'ASC')->select('wilayah')->findAll());
        $wilayahId = array_map('current', $this->wilayahModel->orderBy('id_wilayah', 'ASC')->select('id_wilayah')->findAll());
        $sudahUpload = $this->putaranModel->sudahUpload($this->tahun, $this->id_kuartal, $this->putaran, $wilayahId);
        $monitoring = [
            'isActive' => $this->isActive,
            'tahun' => $this->tahun,
            'id_kuartal' => $this->id_kuartal,
            'putaran' => $this->putaran,
            'wilayah' => $wilayah,
            'status' => $sudahUpload['status'],
            'waktu_upload' => $sudahUpload['upload_at'],
            'diupload_oleh' => $sudahUpload['upload_by'],
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

        return redirect()->back();
    }
}
