<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ArahRevisiController extends BaseController
{
    public function index()
    {
        //
        $data = [
            'title' => 'Rupiah | Arah Revisi',
            'tajuk' => 'Arah Revisi',
            'subTajuk' => 'Arah Revisi Kota (PKRT 7 Komponen)'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/arahRevisi/arahRevisi');
        echo view('layouts/footer');
    }
}
