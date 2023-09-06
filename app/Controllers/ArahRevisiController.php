<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ArahRevisiController extends BaseController
{
    public function index()
    {
        //
        $data = [
            'title' => 'Arah Revisi',
            'tajuk' => 'arahRevisi',
            'subTajuk' => ''
        ];

        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('arahRevisi');
        echo view('layouts/footer');
    }
}
