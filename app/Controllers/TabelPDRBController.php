<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TabelPDRBController extends BaseController
{
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
            'subTajuk' => 'Tabel Ringkasan PDRB Kab/Kota'
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
    public function viewTabelPerKota_12()
    {
        //
        $data = [
            'title' => 'Rupiah | Tabel Per Kota',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel PDRB Per Kota (PKRT 12 Komponen)'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelPerKota12');
        echo view('layouts/footer');
    }
    public function viewTabelHistoryPutaran()
    {
        //
        $data = [
            'title' => 'Rupiah | Tabel History Putaran',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel History Putaran'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelHistoryPutaran');
        echo view('layouts/footer');
    }
}
