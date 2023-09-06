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
            'subTajuk' => ''
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('arahRevisi');
        echo view('layouts/footer');
    }
}
