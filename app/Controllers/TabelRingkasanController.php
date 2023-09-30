<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PDO;

class TabelRingkasanController extends BaseController
{
    public function index($segment)
    {
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'tabelPDRB',
            'subTajuk' => 'tabelRingkasan'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/' . $segment);
        echo view('layouts/footer');
    }

    public function viewPerbandinganPertumbuhanQ()
    {
    }
}
