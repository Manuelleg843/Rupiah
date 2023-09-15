<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiskrepansiModel;
use App\Models\Komponen7;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;

class TabelPDRBController extends BaseController
{
    protected $nilaiDiskrepansi;
    protected $komponen7;
    protected $komponen12;
    protected $putaran;


    public function __construct()
    {
        $this->nilaiDiskrepansi = new DiskrepansiModel();
        $this->komponen7 = new Komponen7Model();
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

    public function viewTabelRingkasan()
    {
        //
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel Ringkasan PDRB Kab/Kota',
            'nilaiDiskrepansi' => $this->nilaiDiskrepansi->get_data(),
            'komponen7' => $this->komponen7->get_data(),
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
            'subTajuk' => 'Tabel PDRB Per Kota (PKRT 7 Komponen)'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelPerKota');
        echo view('layouts/footer');
    }

    public function viewTabelHistoryPutaran()
    {
        //
        $data = [
            'title' => 'Rupiah | Tabel History Putaran',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel History Putaran',
            'komponen7' => $this->komponen7->get_data(),
            'nilai' => $this->putaran->get_data()
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelHistoryPutaran', $data);
        echo view('layouts/footer');
    }
}
