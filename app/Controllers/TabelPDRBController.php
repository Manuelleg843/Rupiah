<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TabelPDRBController extends BaseController
{
    public function index()
    {
        //
        $data = [
            'title' => 'Tabel Ringkasan',
            'tajuk' => 'tabelPDRB',
            'subTajuk' => 'tabelRingkasan'
        ];
        
        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelRingkasan');
        echo view('layouts/footer');
    }
    
    public function viewTabelRingkasan()
    {
        //
        $data = [
            'title' => 'Tabel Ringkasan',
            'tajuk' => 'tabelPDRB',
            'subTajuk' => 'tabelRingkasan'
        ];

        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelRingkasan');
        echo view('layouts/footer');
    }
    public function viewTabelPerProvinsi()
    {
        //
        $data = [
            'title' => 'Tabel Per Provinsi',
            'tajuk' => 'tabelPDRB',
            'subTajuk' => 'tabelPerProvinsi'
        ];

        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelPerProvinsi');
        echo view('layouts/footer');
    }
    public function viewTabelHistoryPutaran()
    {
        //
        $data = [
            'title' => 'Tabel History Putaran',
            'tajuk' => 'tabelPDRB',
            'subTajuk' => 'tabelHistoryPutaran'
        ];

        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelHistoryPutaran');
        echo view('layouts/footer');
    }
}
