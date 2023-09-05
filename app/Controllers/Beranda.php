<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Beranda extends BaseController
{
    public function index()
    {
        //
        $data = [
            'title' => 'Beranda',
            'tajuk' => 'beranda',
            'subTajuk' => ''
        ];
        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('beranda', $data);
        echo view('layouts/footer');
    }
}
